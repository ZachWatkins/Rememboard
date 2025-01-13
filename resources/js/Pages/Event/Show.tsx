import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { Event as EventModel } from "@/types";
import { formatServerTimestamp } from "@/utils";
import NavLink from "@/Components/NavLink";

export default function Show({ event }: { event: EventModel }) {
    return (
        <AuthenticatedLayout
            header={
                <>
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Event &rarr; {event.name}
                    </h2>
                    <NavLink
                        active={false}
                        href={route("events.edit", event.id)}
                        className="mt-4"
                    >
                        Edit
                    </NavLink>
                </>
            }
        >
            <Head title="Create Event" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {event.countdown && (
                                <p>Countdown: {event.countdown}</p>
                            )}
                            {event.description && (
                                <>
                                    <h3>Description</h3>
                                    <p>{event.description}</p>
                                </>
                            )}
                            {event.latitude && event.longitude && (
                                <>
                                    <p>Latitude: {event.latitude}</p>
                                    <p>Longitude: {event.longitude}</p>
                                </>
                            )}
                            {event.address && (
                                <p className="mb-4">{event.address}</p>
                            )}
                            <p>
                                {formatServerTimestamp(event.start_date)}
                                {event.end_date &&
                                    event.end_date !== event.start_date && (
                                        <>
                                            {" "}
                                            to{" "}
                                            {formatServerTimestamp(
                                                event.end_date
                                            )}
                                        </>
                                    )}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
