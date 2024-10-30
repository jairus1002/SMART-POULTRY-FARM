import africastalking
import requests

# Initialize Africa's Talking SDK with sandbox credentials
username = "sandbox"
api_key = "atsk_7ab5eb7344b16d5b8bb583e8eb49d0d2a18e7b91ff86389f0887366b4dea08ac587b9eae"
africastalking.initialize(username, api_key)
sms = africastalking.SMS

# PHP script URL
php_url = "http://localhost/PROJECT_2/sendalert.php"

# Fetch phone numbers and alerts from the PHP script
response = requests.get(php_url)

if response.status_code == 200:
    data = response.json()

    if "error" in data:
        print(data["error"])
        exit()

    phone_numbers = data["phoneNumbers"]
    messages = data["messages"]

    # Helper function to format phone numbers
    def format_phone_number(number):
        if not number:  # Check for None or empty string
            return None
        if number.startswith("07"):
            return "+254" + number[1:]  # Replace '07' with '+254'
        elif number.startswith("+254"):
            return number  # Already in correct format
        else:
            return None  # Invalid phone number format

    # Send SMS messages
    for number in phone_numbers:
        formatted_number = format_phone_number(number)
        if formatted_number:
            for message in messages:
                try:
                    result = sms.send(message, [formatted_number])
                    print(f"SMS sent successfully to {formatted_number}: {result}")
                except Exception as e:
                    print(f"Error sending SMS to {formatted_number}: {e}")
        else:
            print(f"Skipping invalid or empty phone number: {number}")
else:
    print("Failed to retrieve data. HTTP Status:", response.status_code)
