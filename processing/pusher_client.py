import os

from dotenv import load_dotenv
from pusher import pusher

load_dotenv()

pusher_client = pusher.Pusher(
    app_id=os.environ.get("PUSHER_APP_ID"),
    key=os.environ.get("PUSHER_APP_KEY"),
    secret=os.environ.get("PUSHER_APP_SECRET"),
    cluster=os.environ.get("PUSHER_APP_CLUSTER"),
    ssl=True,
)
