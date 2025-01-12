import { FormEventHandler } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from "@inertiajs/react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";

export default function Import() {
    const {
        setData,
        post,
        processing,
        errors,
    } = useForm({
        file: null as File | null,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post("/events/upload", {
            preserveScroll: true,
        });
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Import Events
                </h2>
            }
        >
            <Head title="Import Events" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit}>
                                <InputLabel value="File" htmlFor="file" />
                                <input
                                    name="file"
                                    type="file"
                                    className="block w-full mt-1 form-input"
                                    onChange={(e) =>
                                        setData("file", e.target.files && e.target.files.length ? e.target.files[0] : null)
                                    }
                                />
                                <InputError message={errors.file} />

                                <PrimaryButton
                                    className="mt-4"
                                    disabled={processing}
                                >
                                    Upload File
                                </PrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
