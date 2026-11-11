import type { Auth } from '@/types/auth';
import type { Organization } from '@/types/core';

declare module 'react' {
    interface InputHTMLAttributes<T> {
        passwordrules?: string;
    }
}

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            auth: Auth;
            organization: Organization | null;
            active_modules: string[];
            sidebarOpen: boolean;
            flash: { success?: string; error?: string };
            [key: string]: unknown;
        };
    }
}
