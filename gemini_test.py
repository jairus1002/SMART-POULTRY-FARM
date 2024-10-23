import openai

# Set your OpenAI API key
openai.api_key = "AIzaSyB0exgFLLDaljxqXN2qPtQ2AVdDDRLoQaw"

def get_disease_symptoms(symptoms):
    try:
        # Create a prompt for the OpenAI model
        prompt = f"The farmer has reported the following symptoms in poultry: {symptoms}. What could be the possible diseases?"

        # Call the OpenAI API
        response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",  # Use the desired model
            messages=[
                {"role": "user", "content": prompt}
            ]
        )

        # Extract the response text
        disease_info = response['choices'][0]['message']['content']
        return disease_info
    except Exception as e:
        return str(e)

if __name__ == "__main__":
    # Prompt the user for symptoms
    symptoms = input("Enter the symptoms of the poultry: ")
    disease_info = get_disease_symptoms(symptoms)
    print(f"Possible diseases: {disease_info}")
