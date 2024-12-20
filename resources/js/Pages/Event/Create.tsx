import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { PageProps, Event as EventModel } from "@/types";
import { Head, useForm } from "@inertiajs/react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import DateInput from "@/Components/DateInput";
import PrimaryButton from "@/Components/PrimaryButton";

export default function Create(props: PageProps) {

    const { data, setData, post, processing, errors } = useForm({
        name: "",
        description: "",
        start_date: "",
        end_date: "",
        latitude: "",
        longitude: "",
        city: "",
        state: "",
    });
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Create Event
                </h2>
            }
        >
            <Head title="Create Event" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form
                                onSubmit={(e) => {
                                    e.preventDefault();
                                    post("/events", {
                                        onSuccess: () => {
                                            setData({
                                                name: "",
                                                description: "",
                                                start_date: "",
                                                end_date: "",
                                                latitude: "",
                                                longitude: "",
                                                city: "",
                                                state: "",
                                            });
                                        },
                                    });
                                }}
                            >
                                <InputLabel value="Name" htmlFor="name" />
                                <TextInput
                                    id="name"
                                    value={data.name}
                                    onChange={(e) => setData("name", e.target.value)}
                                />
                                <InputError message={errors.name} />

                                <InputLabel value="Description" htmlFor="description" />
                                <TextInput
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData("description", e.target.value)}
                                />
                                <InputError message={errors.description} />

                                <InputLabel value="Start Date" htmlFor="start_date" />
                                <DateInput
                                    id="start_date"
                                    value={data.start_date}
                                    onChange={(e) => setData("start_date", e.target.value)}
                                    />
                                <InputError message={errors.start_date} />

                                <InputLabel value="End Date" htmlFor="end_date" />
                                <DateInput
                                    id="end_date"
                                    value={data.end_date}
                                    onChange={(e) => setData("end_date", e.target.value)}
                                />
                                <InputError message={errors.end_date} />

                                <InputLabel value="Latitude" htmlFor="latitude" />
                                <TextInput
                                    id="latitude"
                                    value={data.latitude}
                                    onChange={(e) => setData("latitude", e.target.value)}
                                />
                                <InputError message={errors.latitude} />

                                <InputLabel value="Longitude" htmlFor="longitude" />
                                <TextInput
                                    id="longitude"
                                    value={data.longitude}
                                    onChange={(e) => setData("longitude", e.target.value)}
                                />
                                <InputError message={errors.longitude} />

                                <InputLabel value="City" htmlFor="city" />
                                <TextInput
                                    id="city"
                                    value={data.city}
                                    onChange={(e) => setData("city", e.target.value)}
                                />
                                <InputError message={errors.city} />

                                <InputLabel value="State" htmlFor="state" />
                                <TextInput
                                    id="state"
                                    value={data.state}
                                    onChange={(e) => setData("state", e.target.value)}
                                />
                                <InputError message={errors.state} />

                                <PrimaryButton
                                    className="mt-4"
                                    disabled={processing}
                                >
                                    Create Event
                                </PrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
