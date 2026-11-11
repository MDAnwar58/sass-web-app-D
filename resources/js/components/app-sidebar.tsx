import { Link, usePage } from '@inertiajs/react';
import {
    BookOpen, FolderGit2, LayoutGrid, Package,
    ShoppingCart, Users, BarChart2, GraduationCap,
    Settings
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarGroup,
    SidebarGroupLabel,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const coreNav: NavItem[] = [
    { title: 'Dashboard', href: '/dashboard', icon: LayoutGrid },
    { title: 'Settings', href: '/settings', icon: Settings },
];

const inventoryNav: NavItem[] = [
    { title: 'Products', href: '/inventory/products', icon: Package },
    { title: 'Sales', href: '/inventory/sales', icon: ShoppingCart },
    { title: 'Customers', href: '/inventory/customers', icon: Users },
    { title: 'Reports', href: '/inventory/reports', icon: BarChart2 },
];

const lmsNav: NavItem[] = [
    { title: 'Courses', href: '/lms/courses', icon: GraduationCap },
    { title: 'Enrollments', href: '/lms/enrollments', icon: Users },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const { active_modules } = usePage().props as { active_modules: string[] };

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={coreNav} />

                {active_modules?.includes('inventory') && (
                    <SidebarGroup className="px-2 py-0">
                        <SidebarGroupLabel>Inventory</SidebarGroupLabel>
                        <NavMain items={inventoryNav} />
                    </SidebarGroup>
                )}

                {active_modules?.includes('lms') && (
                    <SidebarGroup className="px-2 py-0">
                        <SidebarGroupLabel>Education</SidebarGroupLabel>
                        <NavMain items={lmsNav} />
                    </SidebarGroup>
                )}
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
