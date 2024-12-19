export function formatServerTimestamp(value: string): string {
    return new Date(value).toLocaleString("en-US", {
        month: "long",
        day: "numeric",
        year: "numeric",
        hour12: true,
        hour: "numeric",
        minute: "numeric",
        timeZoneName: "short",
    });
}

export function setTimezoneOffsetHeader() {
    window.axios.defaults.headers.common["X-Timezone-Offset"] = new Date().getTimezoneOffset() * -1;
}
