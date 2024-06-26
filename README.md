# capstonerevamp

This project is aimed at demonstrating how to install required Python packages and libraries for a Flask application and utilizing the Natural Language Toolkit (nltk) for text processing.

## Installation

1. **Create and Activate Virtual Environment**:

    Before installing the required packages, it's recommended to create a virtual environment to isolate your project's dependencies. You can do this by running the following commands:

    ```bash
    # Create a virtual environment named 'venv'
    python -m venv venv

    # Activate the virtual environment
    # On Windows
    venv\Scripts\activate
    # On macOS/Linux
    source venv/bin/activate
    ```

2. **Install Required Packages**:

    Once the virtual environment is activated, install the required packages using pip:

    ```bash
    pip install Flask torch torchvision nltk Flask-Admin Flask-Cors Flask-Login Flask-SQLAlchemy Flask-WTF       
    pip install torch goose3 better_profanity
    pip install numpy nltk
    pip install Flask better_profanity flask-cors
    pip install numpy torch nltk
    pip install gevent mysql-connector-python
    ```

## Usage

1. **Import NLTK**:

    After installing the `nltk` library, you can import it in your Python code:

    ```python
    import nltk
    ```

2. **Download NLTK Data**:

    Before using certain NLTK functionalities, it's necessary to download additional data. For example, to download the `punkt` tokenizer models:

    ```python
    nltk.download('punkt')
    ```

3. **Usage Example**:

    Here's a simple example demonstrating how to tokenize a sentence using NLTK:

    ```python
    from nltk.tokenize import word_tokenize

    sentence = "This is a sample sentence."
    tokens = word_tokenize(sentence)
    print(tokens)
    ```

## Additional Instructions

- If using the WordNet corpus, you need to download it separately:

    ```bash
    python -m nltk.downloader wordnet
    ```

- Some packages may require additional dependencies or setup. Refer to their documentation for more information.

- Make sure to set up your Flask application according to your project's requirements.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)