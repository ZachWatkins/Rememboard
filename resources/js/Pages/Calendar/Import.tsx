import { FormEvent } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { useForm } from "@inertiajs/react";
import FileInput from "@/Components/FileInput";
import InputLabel from "@/Components/InputLabel";
import Checkbox from "@/Components/Checkbox";
import PrimaryButton from "@/Components/PrimaryButton";

export default function Import() {
    const { data, setData, post, progress } = useForm({
        file: null,
        request_coordinates: false,
    });

    function submit(e: FormEvent) {
        e.preventDefault();
        post("/calendar/import");
    }

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Import Calendar Events
                </h2>
            }
        >
            <Head title="Calendar" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit}>
                                <div className="grid grid-cols-12 gap-4">
                                    <InputLabel className="col-span-2" value="File" htmlFor="file" />
                                    <FileInput
                                        className="col-span-10"
                                        name="file"
                                        value={data.file || ""}
                                        onChange={(e) =>
                                            setData(
                                                "file",
                                                Array.isArray(e.target.files)
                                                    ? e.target.files[0]
                                                    : null
                                            )
                                        }
                                    />
                                    <InputLabel className="col-span-2">
                                        Request coordinates
                                    </InputLabel>
                                    <Checkbox
                                        className="col-span-7"
                                        checked={data.request_coordinates}
                                        onChange={(e) =>
                                            setData(
                                                "request_coordinates",
                                                e.target.checked
                                            )
                                        }
                                    />
                                    {progress && (
                                        <progress
                                            value={progress.percentage}
                                            max="100"
                                            className="col-span-12 w-full mt-4"
                                        >
                                            {progress.percentage}%
                                        </progress>
                                    )}
                                    <div className="col-span-12">
                                    <PrimaryButton>
                                        Submit
                                    </PrimaryButton>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
