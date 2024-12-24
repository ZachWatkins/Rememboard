import { PageProps, Event } from "@/types";
import { Head, Link } from "@inertiajs/react";
import { formatServerTimestamp } from "@/utils";
import EventCard from "@/Pages/Event/Partials/EventCard";
import Map from "@/Pages/Partials/Map";

export default function Welcome({
    auth,
    events,
    now,
}: PageProps<{
    now: string;
    events: Event[];
}>) {
    const handleImageError = () => {
        document
            .getElementById("screenshot-container")
            ?.classList.add("!hidden");
        document.getElementById("docs-card")?.classList.add("!row-span-1");
        document
            .getElementById("docs-card-content")
            ?.classList.add("!flex-row");
        document.getElementById("background")?.classList.add("!hidden");
    };

    return (
        <>
            <Head title="Welcome" />
            <div className="bg-white text-black/50 dark:bg-black dark:text-white/50 overflow-hidden">
                <div className="relative">
                    <img
                        id="background"
                        className="absolute -left-20 top-0 max-w-[877px]"
                        src="https://laravel.com/assets/img/welcome/background.svg"
                    />
                </div>
                <div className="relative flex min-h-screen flex-col items-center justify-center w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header className="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div className="lg:col-start-2 lg:justify-center text-center">
                            <h1 className="block text-2xl text-white">
                                Family Calendar
                            </h1>
                            {formatServerTimestamp(now)}
                        </div>
                        <nav className="mx-3 flex flex-1 justify-end">
                            {auth.user ? (
                                <Link
                                    href={route("dashboard")}
                                    className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route("login")}
                                        className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href={route("register")}
                                        className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Register
                                    </Link>
                                </>
                            )}
                        </nav>
                    </header>

                    <main className="mt-6 relative w-full max-w-2xl px-6 lg:max-w-7xl mt-auto mb-auto">
                        <h2 className="text-2xl font-bold text-center">
                            Upcoming Events
                        </h2>
                        <div className="grid gap-6 lg:grid-cols-2 lg:gap-8 mt-6">
                            {/* List of events with days, minutes, and seconds until the event. */}
                            {events &&
                                events
                                    .filter((event) => event.countdown)
                                    .map((event, i) => (
                                        <EventCard key={i} event={event} />
                                    ))}
                        </div>
                        <h2 className="text-2xl font-bold text-center mt-6">
                            Family Trips
                        </h2>
                        {events &&
                            events.filter(
                                (event) =>
                                    event.latitude &&
                                    event.longitude &&
                                    !event.countdown
                            ).length > 0 && (
                                <Map
                                    events={events.filter(
                                        (event) =>
                                            event.latitude &&
                                            event.longitude &&
                                            !event.countdown
                                    )}
                                />
                            )}
                    </main>

                    <footer className="py-16 text-center text-sm text-black dark:text-white/70">
                        &copy; {new Date().getFullYear()} Zachary Watkins
                    </footer>
                </div>
            </div>
        </>
    );
}
