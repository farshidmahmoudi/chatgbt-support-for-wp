function toggleChatWindow() {
    var chatWindow = document.getElementById('chatgpt-support-window');
    if (chatWindow.style.display === 'none' || chatWindow.style.display === '') {
        chatWindow.style.display = 'block';
    } else {
        chatWindow.style.display = 'none';
    }
}

function sendToChatGPT() {
    var query = document.getElementById('chatgpt-support-query').value;
    var apiKey = chatgptSupport.apiKey;

    fetch('https://api.openai.com/v1/engines/davinci-codex/completions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + apiKey
        },
        body: JSON.stringify({
            prompt: query,
            max_tokens: 150
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.choices && data.choices.length > 0) {
            document.getElementById('chatgpt-support-response').innerText = data.choices[0].text.trim();
        } else {
            document.getElementById('chatgpt-support-response').innerText = 'No response received from ChatGPT.';
        }
    })
    .catch(error => {
        document.getElementById('chatgpt-support-response').innerText = 'Error: ' + error.message;
    });
}
