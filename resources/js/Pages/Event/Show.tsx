import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { Event as EventModel } from "@/types";

export default function Show({ event }: { event: EventModel }) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    {event.name}
                </h2>
            }
        >
            <Head title="Create Event" />
            <div>
                <p>Countdown: {event.countdown}</p>
                <h3>Description</h3>
                <p>{event.description}</p>
                <h3>Dates</h3>
                <p>Start Date: {event.start_date}</p>
                <p>End Date: {event.end_date}</p>
                <h3>Location</h3>
                <p>Latitude: {event.latitude}</p>
                <p>Longitude: {event.longitude}</p>
                <p>City: {event.city}</p>
                <p>State: {event.state}</p>
            </div>
        </AuthenticatedLayout>
    );
}
