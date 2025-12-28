<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ShoppingCart } from 'lucide-vue-next';
import productsRoute from '@/routes/products';
import cart from '@/routes/cart';

interface Product {
    id: number;
    name: string;
    description: string | null;
    price: number;
    stock_quantity: number;
    image_url: string | null;
}

defineProps<{
    products: Product[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: productsRoute.index().url,
    },
];

const addToCart = (productId: number) => {
    router.post(cart.store().url, {
        product_id: productId,
        quantity: 1,
    }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Products</h1>
                    <p class="text-muted-foreground">Browse our collection</p>
                </div>
                <Link :href="cart.index().url">
                    <Button variant="outline">
                        <ShoppingCart class="mr-2 h-4 w-4" />
                        View Cart
                    </Button>
                </Link>
            </div>

            <div v-if="products.length === 0" class="flex h-[400px] items-center justify-center rounded-xl border border-dashed">
                <div class="text-center">
                    <p class="text-muted-foreground">No products available</p>
                </div>
            </div>

            <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <Card v-for="product in products" :key="product.id" class="flex flex-col">
                    <CardHeader>
                        <div v-if="product.image_url" class="mb-4 aspect-square overflow-hidden rounded-md">
                            <img :src="product.image_url" :alt="product.name" class="h-full w-full object-cover" />
                        </div>
                        <div v-else class="mb-4 flex aspect-square items-center justify-center rounded-md bg-muted">
                            <ShoppingCart class="h-12 w-12 text-muted-foreground" />
                        </div>
                        <CardTitle>{{ product.name }}</CardTitle>
                        <CardDescription v-if="product.description">
                            {{ product.description.substring(0, 80) }}{{ product.description.length > 80 ? '...' : '' }}
                        </CardDescription>
                    </CardHeader>

                    <CardContent class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold">${{ product.price.toFixed(2) }}</span>
                            <Badge v-if="product.stock_quantity < 10" variant="destructive">
                                Low Stock
                            </Badge>
                            <Badge v-else variant="secondary">
                                In Stock
                            </Badge>
                        </div>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{ product.stock_quantity }} available
                        </p>
                    </CardContent>

                    <CardFooter class="flex gap-2">
                        <Link :href="productsRoute.show(product.id).url" class="flex-1">
                            <Button variant="outline" class="w-full">
                                View Details
                            </Button>
                        </Link>
                        <Button
                            @click="addToCart(product.id)"
                            :disabled="product.stock_quantity === 0"
                            class="flex-1"
                        >
                            <ShoppingCart class="mr-2 h-4 w-4" />
                            Add to Cart
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
