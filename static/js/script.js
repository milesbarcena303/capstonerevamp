class Chatbox {
    constructor() {
        this.args = {
            openButton: document.querySelector('.chatbox__button'),
            chatBox: document.querySelector('.chatbox__support'),
            sendButton: document.querySelector('.send__button')
        };

        this.state = false;
        this.messages = [];

        this.predictEndpoint = 'http://192.168.254.116:5000/predict';
    }

    display() {
        const { openButton, chatBox, sendButton } = this.args;

        openButton.addEventListener('click', () => this.toggleState(chatBox));

        sendButton.addEventListener('click', () => this.onSendButton(chatBox));

        const node = chatBox.querySelector('input');
        node.addEventListener('keyup', ({ key }) => {
            if (key === 'Enter') {
                this.onSendButton(chatBox);
            } 
            
        });

        // Call suggestTopics method automatically when the chatbox is displayed
        this.suggestTopics(chatBox);
       
    }

    
    
    suggestTopics(chatBox) {
        const suggestMessage = { name: 'CVSU Admission System', message: 'Hello! Here are some suggested topics:' };
        this.messages.push(suggestMessage);
    
        const suggestedTopics = ['Admission result', 'Eligibility criteria', 'Documentation requirements', 'Application status', 'Application period'];
    
        suggestedTopics.forEach((topic) => {
            const isClickable = topic !== 'Hello! Here are some suggested topics:';
            const suggestTopicMessage = { name: 'CVSU Admission System', message: topic, clickable: isClickable };
            this.messages.push(suggestTopicMessage);
        });
    
        this.updateChatText(chatBox);
    
      
        const nonClickableMessages = chatBox.querySelectorAll('.messages__item:not(.clickable)');
        nonClickableMessages.forEach((message) => {
            message.classList.remove('clickable');
        });
    
        const clickableMessages = chatBox.querySelectorAll('.clickable');
        clickableMessages.forEach((message) => {
            message.addEventListener('click', () => this.onSuggestedTopicClick(message.getAttribute('data-topic'), chatBox));
        });
    }
    
    
   
    onSuggestedTopicClick(topic, chatBox) {
        // Simulate delay before sending response
        setTimeout(() => {
            fetch('http://192.168.254.116:5000/predict', {
                method: 'POST',
                body: JSON.stringify({ message: topic }),
                mode: 'cors',
                headers: {
                    'Content-Type': 'application/json'
                },
            })
            .then(r => r.json())
            .then(r => {
                const suggestMessage = { name: 'CVSU Admission System', message: 'Hello! Here are the FAQs of the topic :', clickable: false };
                this.messages.push(suggestMessage);
                const responses = r.answer.split('\n');
                responses.forEach(response => {
                    let msg = { name: 'CVSU Admission System', message: response, clickable: true };
                    this.messages.push(msg);
                });
                
                this.updateChatText(chatBox);
                
                // Add click event listeners to newly generated clickable messages
                const clickableMessages = chatBox.querySelectorAll('.clickable');
                clickableMessages.forEach((message) => {
                    message.addEventListener('click', () => this.onUserClickMessage(message.getAttribute('data-topic'), chatBox));
                });
            })
            .catch((error) => {
                console.error('Error:', error);
                this.updateChatText(chatBox);
            });
        }, 2000); // Delay of 2 seconds (2000 milliseconds)
    }
    
    
    onUserClickMessage(topic, chatBox) {
        // Simulate delay before sending response
        setTimeout(() => {
            // Add user's message to the chat history
            let msg = { name: 'User', message: topic };
            this.messages.push(msg);
    
            // Send the user's message to the server
            fetch('http://192.168.254.116:5000/predict', {
                method: 'POST',
                body: JSON.stringify({ message: topic }),
                mode: 'cors',
                headers: {
                    'Content-Type': 'application/json'
                },
            })
            .then(r => r.json())
            .then(r => {
                // Process the server's response
                console.log('Server Response:', r);
                let responseMsg = { name: 'CVSU Admission System', message: r.answer };
                this.messages.push(responseMsg);
        
                // Update the chat text
                this.updateChatText(chatBox);
        
                // After the message is sent and response received, ask for more queries
                this.afterMessageSent(chatBox);
            })
            .catch((error) => {
                // Handle errors from the server
                console.error('Error:', error);
                this.updateChatText(chatBox);
            });
        }, 2000); // Delay of 2 seconds (2000 milliseconds)
    }

    sendMessage(message, chatBox) {
      
        this.messages.push({ name: 'User', message });
        this.updateChatText(chatBox);
    }

    toggleState(chatbox) {
        this.state = !this.state;

        // show or hide the box
        if (this.state) {
            chatbox.classList.add('chatbox--active');
        } else {
            chatbox.classList.remove('chatbox--active');
        }
    }

    onSendButton(chatbox) {
    var textField = chatbox.querySelector('input');
    let text1 = textField.value;
    if (text1 === '') {
        return;
    }

    // Simulate delay before sending response
    setTimeout(() => {
        // Add user's message to the chat history
        let msg1 = { name: 'User', message: text1 };
        this.messages.push(msg1);
        
        // Send the user's message to the server
        fetch('http://192.168.254.116:5000/predict', {
            method: 'POST',
            body: JSON.stringify({ message: text1 }),
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json'
            },
        })
        .then(r => r.json())
        .then(r => {
            // Process the server's response
            let msg2 = { name: 'CVSU Admission System', message: r.answer };
            this.messages.push(msg2);

            // Update the chat text and clear the input field
            this.updateChatText(chatbox);
            this.afterMessageSent(chatbox);
            textField.value = '';
        })
        .catch((error) => {
            // Handle errors from the server
            console.error('Error:', error);
            
            // Update the chat text and clear the input field
            this.updateChatText(chatbox);
            textField.value = '';
        });
    }, 500); // Delay of 2 seconds (2000 milliseconds)
}

    afterMessageSent(chatBox) {
        setTimeout(() => {
        const confirmationMessage = {
            name: 'CVSU Admission System',
            message: 'Is there anything else you need?'
        };
        this.messages.push(confirmationMessage);
    
        const yesButton = {
            name: 'CVSU Admission System',
            message: 'Yes',
            action: 'yes',
            clickable: true
        };
        const noButton = {
            name: 'CVSU Admission System',
            message: 'No',
            action: 'no',
            clickable: true
        };
        this.messages.push(yesButton);
        this.messages.push(noButton);
    
        // Update the chat text
        this.updateChatText(chatBox);
    
    
        
            const yesButtonElement = chatBox.querySelector('[data-topic="Yes"]');
            const noButtonElement = chatBox.querySelector('[data-topic="No"]');
            
            yesButtonElement.addEventListener('click', () => this.handleConfirmation('yes', chatBox));
            noButtonElement.addEventListener('click', () => this.handleConfirmation('no', chatBox));
        }, 5000);
    }
    
    handleConfirmation(action, chatBox) {
        if (action === 'yes') {
            this.suggestTopics(chatBox);
        } else if (action === 'no') {
            // Handle the case where user clicks 'No'
            const goodbyeMessage = {
                name: 'CVSU Admission System',
                message: 'Thank you for contacting us. Have a great day!'
            };
            this.messages.push(goodbyeMessage);
        
            // Update the chat text with the goodbye message
           
            this.updateChatText(chatBox);
        }
    }
    
    
    updateChatText(chatbox) {
       
            var html = '';
            this.messages.slice().reverse().forEach(function (item, index) {
                if (item.name === 'CVSU Admission System') {
                    html += '<div class="messages__item messages__item--visitor clickable" data-topic="' + item.message + '">' + item.message + '</div>';
                } else {
                    if (item.clickable) {
                        html += '<div class="messages__item messages__item--operator clickable" data-topic="' + item.message + '">' + item.message + '</div>';
                    } else {
                        html += '<div class="messages__item messages__item--operator">' + item.message + '</div>';
                    }
                }
            });
    
        const chatmessage = chatbox.querySelector('.chatbox__messages');
        chatmessage.innerHTML = html;
        chatmessage.scrollTop = chatmessage.scrollHeight;
    
        // Add event listeners to clickable elements
        const clickableMessages = chatbox.querySelectorAll('.clickable');
        clickableMessages.forEach((message) => {
            message.addEventListener('click', () => this.handleConfirmation(message.getAttribute('data-topic'), chatbox));
        });
    }
}

const chatbox = new Chatbox();
chatbox.display();