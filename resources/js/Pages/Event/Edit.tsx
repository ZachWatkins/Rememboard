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
import Checkbox from "@/Components/Checkbox";
import NavLink from "@/Components/NavLink";

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
        post(route("events.update"), {
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
                    Edit Event &rarr; {event.name}
                    <NavLink
                        target="_blank"
                        href={route("events.show", event.id)}
                        className="inline-block ml-4 underline"
                    >
                        View Event
                    </NavLink>
                </h2>
            }
        >
            <Head title="Update Event" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit}>
                                <div className="grid grid-cols-12 gap-4">
                                    <div className="col-span-2 col-start-3">
                                        <CheckboxField
                                            label="Is this a trip?"
                                            key="is_trip"
                                            value={data.is_trip}
                                            setData={setData}
                                            errors={errors}
                                        />
                                    </div>
                                    <div className="col-span-8">
                                        <CheckboxField
                                            label="Show on countdown?"
                                            key="show_on_countdown"
                                            value={data.show_on_countdown}
                                            setData={setData}
                                            errors={errors}
                                        />
                                    </div>
                                    <TextField
                                        label="Name"
                                        key="name"
                                        value={data.name}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <TextField
                                        label="Description"
                                        key="description"
                                        value={data.name}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <StartEndDates
                                        start_date={data.start_date}
                                        end_date={data.end_date || ""}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <LatitudeLongitudeFields
                                        latitude={data.latitude || 0}
                                        longitude={data.longitude || 0}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <TextField
                                        label="Address"
                                        key="address"
                                        value={data.address || ""}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <TextField
                                        label="Street Address"
                                        key="street_address"
                                        value={data.street_address || ""}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <TextField
                                        label="City"
                                        key="city"
                                        value={data.city || ""}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <TextField
                                        label="State"
                                        key="state"
                                        value={data.state || ""}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <TextField
                                        label="Zip"
                                        key="zip"
                                        value={data.zip || ""}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <TextField
                                        label="Country"
                                        key="country"
                                        value={data.country || ""}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <TextField
                                        label="Timezone"
                                        key="timezone"
                                        value={data.timezone || ""}
                                        setData={setData}
                                        errors={errors}
                                    />
                                    <PrimaryButton
                                        className="col-span-2 mt-4 text-center"
                                        disabled={processing}
                                        type="submit"
                                    >
                                        <span className="w-full">
                                            Save Event
                                        </span>
                                    </PrimaryButton>
                                </div>
                            </form>
                            <form
                                className="text-right"
                                onSubmit={submitDestroy}
                            >
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

function CheckboxField({
    label,
    key,
    value,
    setData,
    errors,
}: {
    label: string;
    key: string;
    value: boolean;
    setData: (key: string, value: any) => void;
    errors: { [key: string]: string };
}) {
    return (
        <>
            <InputLabel
                className="inline-block mr-4"
                value={label}
                htmlFor={key}
            />
            <Checkbox
                className="col-span-6"
                name="show_on_countdown"
                checked={value}
                onChange={(e) => setData(key, e.target.checked)}
            />
            <InputError className="w-full" message={errors[key]} />
        </>
    );
}

function TextField({
    label,
    key,
    value,
    setData,
    errors,
}: {
    label: string;
    key: string;
    value: string;
    setData: (key: string, value: any) => void;
    errors: { [key: string]: string };
}) {
    return (
        <>
            <InputLabel
                className="col-span-2"
                value={label}
                htmlFor={key}
            />
            <TextInput
                className="col-span-10"
                name={key}
                value={value}
                onChange={(e) => setData(key, e.target.value)}
            />
            <InputError className="col-span-10 col-start-3" message={errors[key]} />
        </>
    );
})

function StartEndDates({
    start_date,
    end_date,
    setData,
    errors,
}: {
    start_date: string;
    end_date: string;
    setData: (key: string, value: any) => void;
    errors: { [key: string]: string };
}) {
    return (
        <>
        <div className="col-span-2">
            <InputLabel
                className="inline-block"
                value="Start Date"
                htmlFor="start_date"
            />
            ,{" "}
            <InputLabel
                className="inline-block"
                value="End Date"
                htmlFor="end_date"
            />
        </div>
        <div className="col-span-5">
            <DateInput
                className="col-span-5"
                name="start_date"
                value={start_date}
                onChange={(e) =>
                    setData(
                        "start_date",
                        e.target.value
                    )
                }
            />
            <InputError
                className="col-span-10 col-offset-2"
                message={errors.start_date}
            />
        </div>
        <div className="col-span-5">
            <DateInput
                className="w-full"
                name="end_date"
                value={end_date || ""}
                onChange={(e) =>
                    setData(
                        "end_date",
                        e.target.value
                    )
                }
            />
            <InputError
                className="mt-4"
                message={errors.end_date}
            />
        </div>
        </>
    );
}

export function LatitudeLongitudeFields({
    latitude,
    longitude,
    setData,
    errors,
}: {
    latitude: number;
    longitude: number;
    setData: (key: string, value: any) => void;
    errors: { [key: string]: string };
}) {
    return (
        <>
            <div className="col-span-2">
                <InputLabel
                    className="inline-block"
                    value="Latitude"
                    htmlFor="latitude"
                />
                ,{" "}
                <InputLabel
                    className="inline-block"
                    value="Longitude"
                    htmlFor="longitude"
                />
            </div>
            <div className="col-span-5">
                <TextInput
                    className="w-full"
                    name="latitude"
                    value={latitude || ""}
                    onChange={(e) =>
                        setData(
                            "latitude",
                            Number(e.target.value)
                        )
                    }
                />
                <InputError
                    className="mt-4"
                    message={errors.latitude}
                />
            </div>
            <div className="col-span-5">
                <TextInput
                    className="w-full"
                    name="longitude"
                    value={longitude || ""}
                    onChange={(e) =>
                        setData(
                            "longitude",
                            Number(e.target.value)
                        )
                    }
                />
                <InputError
                    className="mt-4"
                    message={errors.longitude}
                />
            </div>
        </>
    );
}
