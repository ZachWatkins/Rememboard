import { useEffect } from "react";
import { Event } from "@/types";
import { MapContainer, Marker, Popup, TileLayer, useMap } from "react-leaflet";

export default function Map({ events }: { events?: Event[] }) {
    useEffect(() => {
        const link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = "https://unpkg.com/leaflet@1.9.4/dist/leaflet.css";
        link.integrity = "sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=";
        link.crossOrigin = "";
        document.head.appendChild(link);
    }, []);

    return (
        <MapContainer
            className="mt-8"
            center={[39.8283, -98.5795]}
            zoom={4}
            scrollWheelZoom={false}
            style={{ height: "400px", width: "100%" }}
        >
            <TileLayer
                attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            />
            {events &&
                events
                    .filter((event) => event.latitude && event.longitude)
                    .map((event) => (
                        <Marker
                            key={event.id}
                            position={[
                                event.latitude || 0,
                                event.longitude || 0,
                            ]}
                        >
                            <Popup>
                                <h3>{event.name}</h3>
                                <p>{event.description}</p>
                                <p>
                                    {event.city}, {event.state}
                                </p>
                            </Popup>
                        </Marker>
                    ))}
        </MapContainer>
    );
}
