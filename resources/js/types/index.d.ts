export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};

export interface Event {
    id: number;
    name: string;
    description: string;
    start_date: string;
    end_date: string | null;
    latitude: number | null;
    longitude: number | null;
    city: string | null;
    state: string | null;
    countdown: string;
}
