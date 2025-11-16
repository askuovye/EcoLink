function FindProxyForURL(url, host) {
    if (shExpMatch(url, "*/api/*")) {
        return "PROXY localhost:8000";
    }
    return "DIRECT";
}