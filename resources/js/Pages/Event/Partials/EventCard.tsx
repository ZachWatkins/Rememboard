import { Event } from "@/types";
import { formatServerTimestamp } from "@/utils";
export default function EventCard({ event }: { event: Event }) {
    return (
        <div
            key={event.id}
            className="flex flex-col items-center justify-center gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20] text-center"
        >
            <div className="flex-shrink">
                <h2 className="text-2xl font-semibold text-black dark:text-white">
                    {event.name}
                </h2>
                <strong>Countdown: {event.countdown}</strong>
            </div>

            <p className="flex-grow text-sm/relaxed">{event.description}</p>

            <div className="flex-shrink">
                <span className="text-sm/relaxed">
                    {formatServerTimestamp(event.start_date)}
                </span>
            </div>
        </div>
    );
}
