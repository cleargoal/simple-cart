<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ShoppingCart, ArrowLeft } from 'lucide-vue-next';
import { ref } from 'vue';
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

const props = defineProps<{
    product: Product;
}>();

const quantity = ref(1);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: products.index().url,
    },
    {
        title: props.product.name,
        href: products.show(props.product.id).url,
    },
];

const addToCart = () => {
    router.post(cart.store().url, {
        product_id: props.product.id,
        quantity: quantity.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            quantity.value = 1;
        },
    });
};
</script>

<template>
    <Head :title="product.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Link :href="products.index().url">
                    <Button variant="outline" size="sm">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Products
                    </Button>
                </Link>
            </div>

            <Card>
                <CardContent class="p-6">
                    <div class="grid gap-8 md:grid-cols-2">
                        <!-- Product Image -->
                        <div>
                            <div v-if="product.image_url" class="aspect-square overflow-hidden rounded-lg">
                                <img :src="product.image_url" :alt="product.name" class="h-full w-full object-cover" />
                            </div>
                            <div v-else class="flex aspect-square items-center justify-center rounded-lg bg-muted">
                                <ShoppingCart class="h-24 w-24 text-muted-foreground" />
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="flex flex-col gap-6">
                            <div>
                                <div class="flex items-start justify-between">
                                    <h1 class="text-3xl font-bold">{{ product.name }}</h1>
                                    <Badge v-if="product.stock_quantity < 10" variant="destructive">
                                        Low Stock
                                    </Badge>
                                    <Badge v-else variant="secondary">
                                        In Stock
                                    </Badge>
                                </div>
                                <p class="mt-2 text-muted-foreground">
                                    {{ product.stock_quantity }} items available
                                </p>
                            </div>

                            <div>
                                <p class="text-4xl font-bold">${{ product.price.toFixed(2) }}</p>
                            </div>

                            <div v-if="product.description">
                                <h3 class="mb-2 font-semibold">Description</h3>
                                <p class="text-muted-foreground">{{ product.description }}</p>
                            </div>

                            <!-- Add to Cart Form -->
                            <div class="mt-auto">
                                <div class="flex flex-col gap-4">
                                    <div class="flex items-end gap-4">
                                        <div class="flex-1">
                                            <Label for="quantity">Quantity</Label>
                                            <Input
                                                id="quantity"
                                                v-model.number="quantity"
                                                type="number"
                                                min="1"
                                                :max="product.stock_quantity"
                                                :disabled="product.stock_quantity === 0"
                                            />
                                        </div>
                                        <Button
                                            @click="addToCart"
                                            :disabled="product.stock_quantity === 0 || quantity < 1"
                                            size="lg"
                                            class="flex-1"
                                        >
                                            <ShoppingCart class="mr-2 h-5 w-5" />
                                            Add to Cart
                                        </Button>
                                    </div>

                                    <Link :href="cart.index().url">
                                        <Button variant="outline" class="w-full">
                                            View Cart
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
