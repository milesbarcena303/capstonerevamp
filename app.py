from gevent.pywsgi import WSGIServer
from flask import Flask, render_template, request, jsonify
from flask_admin import Admin, BaseView, expose
from flask_admin.contrib.sqla import ModelView
from flask_cors import CORS
from markupsafe import escape
import subprocess
import mysql.connector
import json
from chat import get_response
from better_profanity import profanity
from flask_sqlalchemy import SQLAlchemy


conn = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='test_db'
)

cursor = conn.cursor()

app = Flask(__name__)








CORS(app, resources={r"/train": {"origins": "*"}})  # Allow requests to /train from any origin

with open('intents.json', 'r') as intents_file:
    intents = json.load(intents_file)

@app.get("/")
def index_get():
    return render_template("index.php")

@app.route('/dashboard')
def admin_dashboard():
    return render_template('admin.php')

@app.post("/predict")
def predict():
    text = escape(request.get_json().get("message"))
    suggested_topics = ['Admission result', 'Eligibility criteria', 'Documentation requirements', 'Application status', 'Application period', 'Other']
    # Check if the clicked topic is in the suggested topics
    if text in suggested_topics:
        # If it's a suggested topic, find the matching intent
        matching_intent = find_matching_intent(text)

        if matching_intent:
            responses = matching_intent["responses"]
            response = "\n".join(responses)
        else:
            response = "I don't have specific information for that topic."
    else:
        # Otherwise, proceed with the regular chatbot response
        response = get_response(text)

    # Censor profanity in the response
    censored_response = profanity.censor(response)

    # Use Markup to render the response as plain text
    message = {"answer": censored_response}
    return jsonify(message)

def find_matching_intent(text):
    for intent in intents["intents"]:
        if text.lower() in [pattern.lower() for pattern in intent["patterns"]]:
            return intent
    return None

@app.route('/train', methods=['POST'])
def train_chatbot():
    subprocess.run(['python', 'train.py'])
    return 'Training started'

def contains_profanity(text):
    return profanity.contains_profanity(text)

@app.route('/save_feedback', methods=['POST'])
def save_feedback():
    email = request.form["email"]
    feedmessage = request.form["feedmessage"]

    if contains_profanity(feedmessage):
        return '''
        <script>
            alert("Feedback contains profanity and cannot be saved.");
            window.location.href = '/';  
        </script>
        '''

    # Use parameterized query to insert data safely
    insert_query = "INSERT INTO feedback (email, message) VALUES (%s, %s)"
    cursor.execute(insert_query, (email, feedmessage))
    conn.commit()

    return '''
    <script>
        alert("Thanks for the feedback!");
        window.location.href = '/';  
    </script>
    '''

if __name__ == "__main__":
    http_server = WSGIServer(('0.0.0.0', 5000), app)
    http_server.serve_forever()
