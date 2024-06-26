from starlette.middleware.base import BaseHTTPMiddleware
from starlette.requests import Request


class RequestStateMiddleware(BaseHTTPMiddleware):
    async def dispatch(self, request: Request, call_next):
        request.state.state = {
            "usage": [],
        }
        response = await call_next(request)
        return response
