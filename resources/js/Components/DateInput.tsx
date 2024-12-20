import { InputHTMLAttributes } from "react";

export default function DateInput({
    value,
    ...props
}: InputHTMLAttributes<HTMLInputElement> & {
    value: string;
}) {
    return (
        <input
            {...props}
            type="date"
            className={
                `block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ` +
                (props.className ? props.className : "")
            }
            value={value}
            onChange={(e) => props.onChange && props.onChange(e)}
        />
    );
}

