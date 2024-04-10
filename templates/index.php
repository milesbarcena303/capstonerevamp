<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ url_for('static', filename='css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="{{ url_for('static', filename='images/CvSU-logo-trans.png') }}" sizes="192x192">
    <link href="{{ url_for('static', filename='bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>CvSU Online Student Admission System</title>
</head>
<body>

    <nav class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color: #376d1d;">
        <a class="navbar-brand" href="#">CvSU OSAS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">Log-out</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
            </form>
        </div>
    </nav>
    <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Feedback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/save_feedback" method="POST">
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="feedbackMessage">Feedback Message</label>
                        <textarea placeholder="Write a feedback..." class="form-control" id="message" name="feedmessage" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas sm fa-paper-plane"></i> Submit Feedback</button>
                </form>
            </div>
        </div>
    </div>
</div>






    <div class="container ">
    
    <div class="container ">
         <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <br>
        <br>
        <br>
        
        </div>
        <div class="col-md-4"></div>
    </div>
        <h5>Admission Information</h5>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        *Before you proceed: <br>
            <button class="btn btn-success btn-lg" onclick="loadVideo()">WATCH VIDEO</button>
        </div>
        <div class="col-md-4"></div>
    </div>
    <br>
    <form class="needs-validation" novalidate="" action="" name="form_information" id="form_information" >
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        *Branch<br>
            <select name="branch" id="branch" class="custom-select"><option>CvSU-Gen.Trias</option><option>CvSU-Tanza</option><option>CvSU-Silang</option><option>CvSU-CCC</option><option>CvSU-Bacoor</option><option>CvSU-Imus</option><option>CvSU-Carmona</option><option>CvSU-Naic</option><option selected="">CvSU-Main</option><option>CvSU-Dasma</option></select>            </div>
        <div class="col-md-4"></div>
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
          <br>
          <button class="btn btn-success btn-md" name="saveBranch" id="saveBranch">Save Admission Information</button>            </div>
        <div class="col-md-4"></div>

     </div>
    </form>
  
  </div>
   



        <div class="chatbox resizable">
            <div class="chatbox__support">
            
                <div class="chatbox__header">
                
                    <div class="chatbox__image--header">
                        <img src="static/images/CvSU-logo-trans.png" alt="image" width="50" height="40" >
                       
                    </div>
                    <div class="chatbox__content--header">
                        <h5 class="chatbox__heading--header">Chat support</h5>
                        <p class="chatbox__description--header">CvSU Admission Response System</p>
                    </div>
                    
                    
                    <div class="header-buttons">
                        
                        <button type="button" class="btn-close" aria-label="Close"></button>
                    </div>
                </div>
                <div class="chatbox__messages">
                    <div></div>
                </div>
                <div class="chatbox__footer">
                <button class="feedback-button" data-toggle="modal" data-target="#feedbackModal" style="font-size: 12px; padding: 4px 8px;">
                    <i class="fas sm fa-paperclip"></i> Feedback
                </button>
                <input type="text" placeholder="Write a message..." style="font-size: 16px; padding: 12px;" oninput="validateInput(this)" required>

                <script>
                        function validateInput(input) {
                            var regex = /^[a-zA-Z0-9!?@\s]*$/;
                            if (!regex.test(input.value)) {
                                input.value = input.value.replace(/[^a-zA-Z!?@\s]/g, '');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Please enter only text and the characters !?',
                                });
                            }
                        }
                </script>
                    <button class="chatbox__send--footer send__button" id="sendMessageButton" style="font-size: 12px; padding: 4px 8px;">
                        <i class="fas sm fa-paper-plane"></i> Send
                    </button>
                   
                </div>
                
                <div class="chatbox__feedback">
    
</div>

            </div>
            <div class="chatbox__button">
           
                <button id="chatboxButton"><img src="static/images/CvSU-logo-trans.png" width="50" height="40" /></button>
                
            </div>
        </div>
    </div>

   
    <script>
       

        
        // Add event listener for the chatbox button
        var chatboxButton = document.getElementById('chatboxButton');
        chatboxButton.addEventListener('click', function () {
          
           
        });

        var chatboxMessages = document.querySelector('.chatbox__messages');
        chatboxMessages.textContent = "{{ chatbot_response }}";

        // Function to sanitize and display user message
        function sendMessage() {
            const userMessage = document.getElementById('userMessageInput').value;
            const sanitizedMessage = DOMPurify.sanitize(userMessage);
            displayUserMessage(sanitizedMessage);
        }

        // Function to display user message in the chatbox
        function displayUserMessage(message) {
            const chatboxMessages = document.querySelector('.chatbox__messages');
            const userMessageDiv = document.createElement('div');
            userMessageDiv.classList.add('messages__item', 'messages__item--visitor');
            userMessageDiv.textContent = message;
            chatboxMessages.appendChild(userMessageDiv);
        }


        
    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="{{ url_for('static', filename='bootstrap/purify.min.js') }}"></script>
    <script  src="{{ url_for('static', filename='bootstrap/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ url_for('static', filename='js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
</body>
</html>
