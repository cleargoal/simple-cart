<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ShoppingCart, Package, DollarSign, Heart, ArrowRight, ShoppingBag } from 'lucide-vue-next';
import { dashboard } from '@/routes';
import products from '@/routes/products';
import cart from '@/routes/cart';

interface Product {
    id: number;
    name: string;
    description: string | null;
    price: number;
    stock_quantity: number;
    image_url: string | null;
}

interface OrderProduct {
    id: number;
    name: string;
    image_url: string | null;
}

interface Order {
    id: number;
    created_at: string;
    status: string;
    total_amount: number;
    items_count: number;
    products: OrderProduct[];
}

interface Stats {
    totalOrders: number;
    totalSpent: number;
    cartValue: number;
    wishlistCount: number;
}

defineProps<{
    stats: Stats;
    recentOrders: Order[];
    recentlyViewed: Product[];
    recommendedProducts: Product[];
    wishlistItems: Product[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const getStatusColor = (status: string) => {
    const colors: Record<string, string> = {
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-950 dark:text-yellow-300',
        processing: 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
        completed: 'bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300',
        cancelled: 'bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300',
    };
    return colors[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-950 dark:text-gray-300';
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <p class="text-muted-foreground">Welcome back! Here's an overview of your activity.</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Orders</CardTitle>
                        <Package class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalOrders }}</div>
                        <p class="text-xs text-muted-foreground">All-time orders</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Spent</CardTitle>
                        <DollarSign class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">${{ stats.totalSpent.toFixed(2) }}</div>
                        <p class="text-xs text-muted-foreground">Lifetime spending</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Cart Value</CardTitle>
                        <ShoppingCart class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">${{ stats.cartValue.toFixed(2) }}</div>
                        <p class="text-xs text-muted-foreground">Current cart total</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Wishlist</CardTitle>
                        <Heart class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.wishlistCount }}</div>
                        <p class="text-xs text-muted-foreground">Saved items</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Actions -->
            <Card>
                <CardHeader>
                    <CardTitle>Quick Actions</CardTitle>
                    <CardDescription>Jump to what you need</CardDescription>
                </CardHeader>
                <CardContent class="flex flex-wrap gap-3">
                    <Link :href="products.index().url">
                        <Button variant="outline">
                            <ShoppingBag class="mr-2 h-4 w-4" />
                            Browse Products
                        </Button>
                    </Link>
                    <Link v-if="stats.cartValue > 0" :href="cart.index().url">
                        <Button>
                            <ShoppingCart class="mr-2 h-4 w-4" />
                            View Cart (${{ stats.cartValue.toFixed(2) }})
                        </Button>
                    </Link>
                </CardContent>
            </Card>

            <!-- Recent Orders -->
            <Card v-if="recentOrders.length > 0">
                <CardHeader>
                    <CardTitle>Recent Orders</CardTitle>
                    <CardDescription>Your latest purchases</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div v-for="order in recentOrders" :key="order.id" class="flex items-center justify-between border-b pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center gap-4">
                                <div class="flex -space-x-2">
                                    <div v-for="product in order.products" :key="product.id" class="h-10 w-10 overflow-hidden rounded-full border-2 border-background">
                                        <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="h-full w-full object-cover" />
                                        <div v-else class="flex h-full w-full items-center justify-center bg-muted">
                                            <Package class="h-4 w-4 text-muted-foreground" />
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-medium">Order #{{ order.id }}</p>
                                    <p class="text-sm text-muted-foreground">{{ order.created_at }} • {{ order.items_count }} items</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <Badge :class="getStatusColor(order.status)">
                                    {{ order.status }}
                                </Badge>
                                <p class="font-semibold">${{ order.total_amount.toFixed(2) }}</p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Recently Viewed Products -->
            <Card v-if="recentlyViewed.length > 0">
                <CardHeader>
                    <CardTitle>Recently Viewed</CardTitle>
                    <CardDescription>Products you've looked at</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <Link v-for="product in recentlyViewed" :key="product.id" :href="products.show(product.id).url" class="group">
                            <Card class="overflow-hidden transition-shadow hover:shadow-md">
                                <div v-if="product.image_url" class="aspect-square overflow-hidden">
                                    <img :src="product.image_url" :alt="product.name" class="h-full w-full object-cover transition-transform group-hover:scale-105" />
                                </div>
                                <div v-else class="flex aspect-square items-center justify-center bg-muted">
                                    <ShoppingCart class="h-12 w-12 text-muted-foreground" />
                                </div>
                                <CardContent class="p-4">
                                    <p class="font-medium line-clamp-1">{{ product.name }}</p>
                                    <p class="text-lg font-bold">${{ product.price.toFixed(2) }}</p>
                                </CardContent>
                            </Card>
                        </Link>
                    </div>
                </CardContent>
            </Card>

            <!-- Recommended Products -->
            <Card v-if="recommendedProducts.length > 0">
                <CardHeader>
                    <CardTitle>Recommended for You</CardTitle>
                    <CardDescription>Based on your shopping history</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <Link v-for="product in recommendedProducts" :key="product.id" :href="products.show(product.id).url" class="group">
                            <Card class="overflow-hidden transition-shadow hover:shadow-md">
                                <div v-if="product.image_url" class="aspect-square overflow-hidden">
                                    <img :src="product.image_url" :alt="product.name" class="h-full w-full object-cover transition-transform group-hover:scale-105" />
                                </div>
                                <div v-else class="flex aspect-square items-center justify-center bg-muted">
                                    <ShoppingCart class="h-12 w-12 text-muted-foreground" />
                                </div>
                                <CardContent class="p-4">
                                    <p class="font-medium line-clamp-1">{{ product.name }}</p>
                                    <p class="text-lg font-bold">${{ product.price.toFixed(2) }}</p>
                                </CardContent>
                            </Card>
                        </Link>
                    </div>
                </CardContent>
            </Card>

            <!-- Wishlist Items -->
            <Card v-if="wishlistItems.length > 0">
                <CardHeader>
                    <CardTitle>Your Wishlist</CardTitle>
                    <CardDescription>Items you saved for later</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <Link v-for="product in wishlistItems" :key="product.id" :href="products.show(product.id).url" class="group">
                            <Card class="overflow-hidden transition-shadow hover:shadow-md">
                                <div v-if="product.image_url" class="aspect-square overflow-hidden">
                                    <img :src="product.image_url" :alt="product.name" class="h-full w-full object-cover transition-transform group-hover:scale-105" />
                                </div>
                                <div v-else class="flex aspect-square items-center justify-center bg-muted">
                                    <ShoppingCart class="h-12 w-12 text-muted-foreground" />
                                </div>
                                <CardContent class="p-4">
                                    <p class="font-medium line-clamp-1">{{ product.name }}</p>
                                    <p class="text-lg font-bold">${{ product.price.toFixed(2) }}</p>
                                </CardContent>
                            </Card>
                        </Link>
                    </div>
                </CardContent>
            </Card>

            <!-- Empty State -->
            <Card v-if="stats.totalOrders === 0">
                <CardContent class="flex flex-col items-center justify-center py-12">
                    <ShoppingBag class="h-16 w-16 text-muted-foreground mb-4" />
                    <h3 class="text-xl font-semibold mb-2">Start Shopping</h3>
                    <p class="text-muted-foreground mb-6 text-center">You haven't placed any orders yet. Browse our products to get started!</p>
                    <Link :href="products.index().url">
                        <Button size="lg">
                            Browse Products
                            <ArrowRight class="ml-2 h-4 w-4" />
                        </Button>
                    </Link>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
