import { FormEventHandler } from "react";
import { Event as EventModel } from "@/types";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from "@inertiajs/react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import DateInput from "@/Components/DateInput";
import PrimaryButton from "@/Components/PrimaryButton";
import DangerButton from "@/Components/DangerButton";

export default function Update({ event }: { event: EventModel }) {
    const {
        data,
        setData,
        post,
        processing,
        errors,
        delete: destroy,
    } = useForm(event);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post("/events", {
            preserveScroll: true,
        });
    };
    const submitDestroy: FormEventHandler = (e) => {
        e.preventDefault();
        if (confirm("Are you sure you want to delete this event?")) {
            destroy("/events/" + data.id);
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Update Event
                </h2>
            }
        >
            <Head title="Update Event" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit}>
                                <InputLabel value="Name" htmlFor="name" />
                                <TextInput
                                    id="name"
                                    value={data.name}
                                    onChange={(e) =>
                                        setData("name", e.target.value)
                                    }
                                />
                                <InputError message={errors.name} />

                                <InputLabel
                                    value="Description"
                                    htmlFor="description"
                                />
                                <TextInput
                                    id="description"
                                    value={data.description}
                                    onChange={(e) =>
                                        setData("description", e.target.value)
                                    }
                                />
                                <InputError message={errors.description} />

                                <InputLabel
                                    value="Start Date"
                                    htmlFor="start_date"
                                />
                                <DateInput
                                    id="start_date"
                                    value={data.start_date}
                                    onChange={(e) =>
                                        setData("start_date", e.target.value)
                                    }
                                />
                                <InputError message={errors.start_date} />

                                <InputLabel
                                    value="End Date"
                                    htmlFor="end_date"
                                />
                                <DateInput
                                    id="end_date"
                                    value={data.end_date || ""}
                                    onChange={(e) =>
                                        setData("end_date", e.target.value)
                                    }
                                />
                                <InputError message={errors.end_date} />

                                <InputLabel
                                    value="Latitude"
                                    htmlFor="latitude"
                                />
                                <TextInput
                                    id="latitude"
                                    value={data.latitude || ""}
                                    onChange={(e) =>
                                        setData(
                                            "latitude",
                                            Number(e.target.value)
                                        )
                                    }
                                />
                                <InputError message={errors.latitude} />

                                <InputLabel
                                    value="Longitude"
                                    htmlFor="longitude"
                                />
                                <TextInput
                                    id="longitude"
                                    value={data.longitude || ""}
                                    onChange={(e) =>
                                        setData(
                                            "longitude",
                                            Number(e.target.value)
                                        )
                                    }
                                />
                                <InputError message={errors.longitude} />

                                <InputLabel value="City" htmlFor="city" />
                                <TextInput
                                    id="city"
                                    value={data.city || ""}
                                    onChange={(e) =>
                                        setData("city", e.target.value)
                                    }
                                />
                                <InputError message={errors.city} />

                                <InputLabel value="State" htmlFor="state" />
                                <TextInput
                                    id="state"
                                    value={data.state || ""}
                                    onChange={(e) =>
                                        setData("state", e.target.value)
                                    }
                                />
                                <InputError message={errors.state} />

                                <PrimaryButton
                                    className="mt-4"
                                    disabled={processing}
                                >
                                    Create Event
                                </PrimaryButton>
                            </form>
                            <form onSubmit={submitDestroy}>
                                <DangerButton
                                    className="mt-4"
                                    disabled={processing}
                                >
                                    Delete Event
                                </DangerButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
