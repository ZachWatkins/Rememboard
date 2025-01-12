import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { Event } from "@/types";
import NavLink from "@/Components/NavLink";

export default function Dashboard({ events }: { events: Event[] }) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {/* List all events. */}
                            <div className="text-2xl font-semibold text-gray-800">
                                Events
                            </div>
                            <div className="mt-4">
                                {events.length ? (
                                    <ul>
                                        {events.map((event) => (
                                            <li key={event.id}>
                                                <NavLink
                                                    href={route(
                                                        "events.show",
                                                        event.id
                                                    )}
                                                    active={false}
                                                >
                                                    {event.name}
                                                </NavLink>
                                            </li>
                                        ))}
                                    </ul>
                                ) : (
                                    <p>No events found.</p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
