import { FormEvent } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { useForm } from "@inertiajs/react";

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
                                <input
                                    type="file"
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
                                <label>
                                    <input
                                        type="checkbox"
                                        checked={data.request_coordinates}
                                        onChange={(e) =>
                                            setData(
                                                "request_coordinates",
                                                e.target.checked
                                            )
                                        }
                                    />
                                    Request coordinates
                                </label>
                                {progress && (
                                    <progress
                                        value={progress.percentage}
                                        max="100"
                                    >
                                        {progress.percentage}%
                                    </progress>
                                )}
                                <button type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
