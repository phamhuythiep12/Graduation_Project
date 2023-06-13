from flask import Flask, jsonify, request
from flask_cors import CORS

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

# Define the vocabulary with meanings and image URLs
vocabulary = {
    "topic":{
            "fruit": {
            "1": {
                "id": 1,
                "word": "apple",
                "image_url": "https://img.freepik.com/free-vector/sticker-design-with-apple-isolated_1308-66383.jpg?w=2000"
            },
            "2": {
                "id": 2,
                "word": "banana",
                "image_url": "https://m.media-amazon.com/images/I/51ebZJ+DR4L._AC_UF1000,1000_QL80_.jpg"
            },
            "3": {
                "id": 3,
                "word": "orange",
                "image_url": "https://upload.wikimedia.org/wikipedia/commons/c/c4/Orange-Fruit-Pieces.jpg"
            },
            "4": {
                "id": 4,
                "word": "pineapple",
                "image_url": "https://static.vecteezy.com/system/resources/previews/005/104/874/original/fresh-pineapple-illustration-suitable-for-decoration-sticker-icon-and-others-free-vector.jpg"
            },
            "5":{
                "id": 5,
                "word": "watermelon",
                "image_url": "https://static.toiimg.com/thumb/52255684.cms?resizemode=4&width=1200"
            }
        },
        "family":{
            "1":{
                "id": 1,
                "word": "family",
                "image_url": "https://img.freepik.com/free-vector/children-back-school-with-parents_52683-40883.jpg"
            },
            "2":{
                "id": 2,
                "word": "father",
                "image_url": "https://cdn1.byjus.com/wp-content/uploads/2022/11/my-father-essay-for-class-1-1.jpeg"

            }
        },
        "school":{
            
        },
        "color":{
            
        }
    }
}


# Define the API endpoint for retrieving word meanings and images
@app.route('/word/<word>', methods=['GET'])
def get_word_info(word):
    word_id = request.args.get('id')
    word_info = vocabulary.get(word.lower())

    if word_info:
        if word_id:
            word = word_info.get(word_id.lower())
            if word:
                return jsonify(word)
            else:
                return jsonify({"error": "Word not found"})
        else:
            return jsonify(word_info)
    else:
        return jsonify({"error": "Category not found"})


# Define the route for the root URL
@app.route('/')
def home():
    return "Welcome to the Word API! Please refer to the documentation for usage instructions."


# Define the route for the favicon.ico
@app.route('/favicon.ico')
def favicon():
    return jsonify({"message": "No favicon available."})


# Run the Flask application
if __name__ == '__main__':
    app.run()
