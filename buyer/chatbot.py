from flask import Flask, render_template, request, jsonify
from chatterbot import ChatBot
from chatterbot.trainers import ChatterBotCorpusTrainer

app = Flask(__name__)

# Create a new chatbot
marketplace_bot = ChatBot('MarketplaceBot')

# Create a new trainer for the chatbot
trainer = ChatterBotCorpusTrainer(marketplace_bot)

# Train the chatbot on custom data related to your marketplace
trainer.train([
    'What products are available?',
    'We have a variety of products, including electronics, clothing, and home goods.',
    'Tell me about electronics.',
    'We offer the latest electronic gadgets and devices. What specific product are you looking for?',
    'How can I sell my products on the marketplace?',
    'To sell products, you can create a seller account and list your items through the seller dashboard.',
])

# Route for the main page
@app.route('/')
def index():
    return render_template('index.html')

# Route to handle chatbot responses
@app.route('/get_response', methods=['POST'])
def get_response():
    user_input = request.form['user_input']

    # Get the chatbot's response
    response = marketplace_bot.get_response(user_input).text

    # Return the response as JSON
    return jsonify({'response': response})

if __name__ == '__main__':
    app.run(debug=True)
