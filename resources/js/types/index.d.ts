export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    timezone: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>
> = T & {
    auth: {
        user: User;
    };
};

export interface Event {
    id?: number;
    name: string;
    description: string;
    start_date: string;
    end_date: string | null;
    latitude: number | null;
    longitude: number | null;
    address: string | null;
    city: string | null;
    state: string | null;
    zip: string | null;
    country: string | null;
    timezone: string;
    is_trip: boolean;
    countdown?: string;
}
