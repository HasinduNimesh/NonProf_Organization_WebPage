import requests
import time
import urllib3

# Disable SSL warnings
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

url = "https://hasindunimesh.ct.ws/blog-single.php?id=21"

headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36"
}

for i in range(100):
    try:
        response = requests.get(url, headers=headers, verify=False, timeout=10)
        print(f"Visit {i+1}: Status code {response.status_code}")
    except Exception as e:
        print(f"Visit {i+1}: An error occurred: {e}")
    time.sleep(0.5)  # Add a delay to prevent rate-limiting
