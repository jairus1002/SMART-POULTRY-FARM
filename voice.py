import africastalking

class VoiceService:
    def __init__(self):
        # Set your app credentials (Use the correct API Key here)
        self.username = "sandbox"
        self.api_key = "atsk_7ab5eb7344b16d5b8bb583e8eb49d0d2a18e7b91ff86389f0887366b4dea08ac587b9eae"
        
        # Initialize the SDK
        africastalking.initialize(self.username, self.api_key)
        
        # Get the voice service
        self.voice = africastalking.Voice

    def call(self):
        # Set your Africa's Talking phone number in international format
        call_from = "+254797853542"
        
        # Set the numbers you want to call to in a comma-separated list
        call_to = ["+254732477052"]
        
        try:
            # Make the call
            result = self.voice.call(call_from, call_to)
            print(result)
        
        except Exception as e:
            print(f"Encountered an error while making the call: {str(e)}")

if __name__ == "__main__":
    voice_service = VoiceService()
    voice_service.call()
