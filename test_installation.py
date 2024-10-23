import google.generativeai as genai
import os

# Use the API key from your environment variable
# api_key = os.getenv("AIzaSyDDW_pP_G4aIVB9W975i2Ayn7-r345gxCE")
api_key = "AIzaSyDDW_pP_G4aIVB9W975i2Ayn7-r345gxCE"


if api_key:
    print(f"Using API Key: {api_key}")
else:
    print("API Key not found. Please set the API_KEY environment variable.")

# Configure the client with your API key
genai.configure(api_key=api_key)

# Make a test API call
# response = genai.generate_text(
#     model="models/text-bison-001",
#     prompt="Explain the symptoms of a poultry disease",
# )

# # Print the response
# if response:
#     print(response['candidates'][0]['output'])
# else:
#     print("No response received")


model = genai.GenerativeModel(model_name="gemini-1.5-flash-exp-0827")

# Provide the prompt for text generation
prompt = "Explain the symptoms of a poultry disease."

# Generate text using the model
response = model.generate_content(prompt)

# Access the generated text
generated_text = response.text

print(generated_text)