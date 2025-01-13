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
                                        <InputLabel
                                            className="inline-block mr-4"
                                            value="Is this a trip?"
                                            htmlFor="is_trip"
                                        />
                                        <Checkbox
                                            name="is_trip"
                                            checked={data.is_trip}
                                            onChange={(e) =>
                                                setData(
                                                    "is_trip",
                                                    e.target.checked
                                                )
                                            }
                                        />
                                        <InputError
                                            className="w-full"
                                            message={errors.is_trip}
                                        />
                                    </div>
                                    <div className="col-span-8">
                                        <InputLabel
                                            className="inline-block mr-4"
                                            value="Show on countdown?"
                                            htmlFor="show_on_countdown"
                                        />
                                        <Checkbox
                                            className="col-span-6"
                                            name="show_on_countdown"
                                            checked={data.show_on_countdown}
                                            onChange={(e) =>
                                                setData(
                                                    "show_on_countdown",
                                                    e.target.checked
                                                )
                                            }
                                        />
                                        <InputError
                                            className="w-full"
                                            message={errors.show_on_countdown}
                                        />
                                    </div>
                                    <InputLabel
                                        className="col-span-2"
                                        value="Name"
                                        htmlFor="name"
                                    />
                                    <TextInput
                                        className="col-span-10"
                                        name="name"
                                        value={data.name}
                                        onChange={(e) =>
                                            setData("name", e.target.value)
                                        }
                                    />
                                    <InputError
                                        className="col-span-10 col-start-3"
                                        message={errors.name}
                                    />
                                    <InputLabel
                                        className="col-span-2"
                                        value="Description"
                                        htmlFor="description"
                                    />
                                    <TextInput
                                        className="col-span-10"
                                        name="description"
                                        value={data.description}
                                        onChange={(e) =>
                                            setData(
                                                "description",
                                                e.target.value
                                            )
                                        }
                                    />
                                    <InputError
                                        className="col-span-10 col-start-3"
                                        message={errors.description}
                                    />
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
                                            value={data.start_date}
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
                                            value={data.end_date || ""}
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
                                            value={data.latitude || ""}
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
                                            value={data.longitude || ""}
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
                                    <InputLabel
                                        className="col-span-2"
                                        value="Address"
                                        htmlFor="address"
                                    />
                                    <TextInput
                                        className="col-span-10"
                                        name="address"
                                        value={data.address || ""}
                                        onChange={(e) =>
                                            setData("address", e.target.value)
                                        }
                                    />
                                    <InputError
                                        className="col-span-10 col-offset-2"
                                        message={errors.address}
                                    />
                                    <InputLabel
                                        className="col-span-2"
                                        value="Street Address"
                                        htmlFor="street_address"
                                    />
                                    <TextInput
                                        className="col-span-10"
                                        name="street_address"
                                        value={data.street_address || ""}
                                        onChange={(e) =>
                                            setData(
                                                "street_address",
                                                e.target.value
                                            )
                                        }
                                    />
                                    <InputError
                                        className="col-span-10 col-offset-2"
                                        message={errors.street_address}
                                    />
                                    <InputLabel
                                        className="col-span-2"
                                        value="City"
                                        htmlFor="city"
                                    />
                                    <TextInput
                                        className="col-span-10"
                                        name="city"
                                        value={data.city || ""}
                                        onChange={(e) =>
                                            setData("city", e.target.value)
                                        }
                                    />
                                    <InputError
                                        className="col-span-10 col-start-3"
                                        message={errors.city}
                                    />
                                    <InputLabel
                                        className="col-span-2"
                                        value="State"
                                        htmlFor="state"
                                    />
                                    <TextInput
                                        className="col-span-10"
                                        name="state"
                                        value={data.state || ""}
                                        onChange={(e) =>
                                            setData("state", e.target.value)
                                        }
                                    />
                                    <InputError
                                        className="col-span-10 col-start-3"
                                        message={errors.state}
                                    />
                                    <InputLabel
                                        className="col-span-2"
                                        value="Zip"
                                        htmlFor="zip"
                                    />
                                    <TextInput
                                        className="col-span-10"
                                        name="zip"
                                        value={data.zip || ""}
                                        onChange={(e) =>
                                            setData("zip", e.target.value)
                                        }
                                    />
                                    <InputError
                                        className="col-span-10 col-start-3"
                                        message={errors.zip}
                                    />
                                    <InputLabel
                                        className="col-span-2"
                                        value="Country"
                                        htmlFor="country"
                                    />
                                    <TextInput
                                        className="col-span-10"
                                        name="country"
                                        value={data.country || ""}
                                        onChange={(e) =>
                                            setData("country", e.target.value)
                                        }
                                    />
                                    <InputError
                                        className="col-span-10 col-start-3"
                                        message={errors.country}
                                    />
                                    <InputLabel
                                        className="col-span-2"
                                        value="Timezone"
                                        htmlFor="timezone"
                                    />
                                    <TextInput
                                        className="col-span-10"
                                        name="timezone"
                                        value={data.timezone || ""}
                                        onChange={(e) =>
                                            setData("timezone", e.target.value)
                                        }
                                    />
                                    <InputError
                                        className="col-span-10 col-start-3"
                                        message={errors.timezone}
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
