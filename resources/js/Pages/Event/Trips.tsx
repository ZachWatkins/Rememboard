import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps, Event } from "@/types";
import { Head } from "@inertiajs/react";
import EventCard from "./Partials/EventCard";

export default function Trips({ events }: PageProps<{ events: Event[] }>) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Events
                </h2>
            }
        >
            <Head title="Events" />
            {events && events.length > 0 ? (
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {events.map((event) => (
                        <EventCard key={event.id} event={event} />
                    ))}
                </div>
            ) : (
                <div className="text-center text-gray-500">
                    No events found.
                </div>
            )}
        </AuthenticatedLayout>
    );
}
