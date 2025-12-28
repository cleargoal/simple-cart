<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { ShoppingCart, Trash2, ArrowLeft, Plus, Minus } from 'lucide-vue-next';
import { ref } from 'vue';
import productsRoute from '@/routes/products';
import cartRoute from '@/routes/cart';
import checkoutRoute from '@/routes/checkout';

interface Product {
    id: number;
    name: string;
    price: number;
    stock_quantity: number;
}

interface CartItem {
    id: number;
    quantity: number;
    product: Product;
    subtotal: number;
}

const props = defineProps<{
    cartItems: CartItem[];
    total: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Shopping Cart',
        href: cartRoute.index().url,
    },
];

const quantities = ref<Record<number, number>>(
    Object.fromEntries(props.cartItems.map(item => [item.id, item.quantity]))
);

const updateQuantity = (cartItemId: number) => {
    router.patch(cartRoute.update(cartItemId).url, {
        quantity: quantities.value[cartItemId],
    }, {
        preserveScroll: true,
    });
};

const incrementQuantity = (item: CartItem) => {
    if (quantities.value[item.id] < item.product.stock_quantity) {
        quantities.value[item.id]++;
        updateQuantity(item.id);
    }
};

const decrementQuantity = (item: CartItem) => {
    if (quantities.value[item.id] > 1) {
        quantities.value[item.id]--;
        updateQuantity(item.id);
    }
};

const removeItem = (cartItemId: number) => {
    router.delete(cartRoute.destroy(cartItemId).url, {
        preserveScroll: true,
    });
};

const checkoutHandler = () => {
    router.post(checkoutRoute.store().url, {}, {
        preserveScroll: false,
    });
};
</script>

<template>
    <Head title="Shopping Cart" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <div class="flex items-center gap-4">
                <Link :href="productsRoute.index().url">
                    <Button variant="outline" size="sm">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Continue Shopping
                    </Button>
                </Link>
            </div>

            <div v-if="cartItems.length === 0" class="flex h-[400px] items-center justify-center rounded-xl border border-dashed">
                <div class="text-center">
                    <ShoppingCart class="mx-auto h-12 w-12 text-muted-foreground" />
                    <p class="mt-4 text-lg font-medium">Your cart is empty</p>
                    <p class="text-sm text-muted-foreground">Add some products to get started</p>
                    <Link :href="productsRoute.index().url" class="mt-4 inline-block">
                        <Button>Browse Products</Button>
                    </Link>
                </div>
            </div>

            <div v-else class="grid gap-6 lg:grid-cols-3">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <ShoppingCart class="h-5 w-5" />
                                Cart Items ({{ cartItems.length }})
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div
                                v-for="(item, index) in cartItems"
                                :key="item.id"
                            >
                                <div class="flex items-center gap-4">
                                    <div class="flex-1">
                                        <Link :href="productsRoute.show(item.product.id).url">
                                            <h3 class="font-semibold hover:underline">{{ item.product.name }}</h3>
                                        </Link>
                                        <p class="text-sm text-muted-foreground">${{ item.product.price.toFixed(2) }} each</p>
                                        <p class="text-xs text-muted-foreground">{{ item.product.stock_quantity }} available</p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-1 rounded-md border">
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                class="h-8 w-8"
                                                @click="decrementQuantity(item)"
                                                :disabled="quantities[item.id] <= 1"
                                            >
                                                <Minus class="h-3 w-3" />
                                            </Button>
                                            <span class="w-10 text-center text-sm font-medium">{{ quantities[item.id] }}</span>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                class="h-8 w-8"
                                                @click="incrementQuantity(item)"
                                                :disabled="quantities[item.id] >= item.product.stock_quantity"
                                            >
                                                <Plus class="h-3 w-3" />
                                            </Button>
                                        </div>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            @click="removeItem(item.id)"
                                        >
                                            <Trash2 class="h-4 w-4 text-destructive" />
                                        </Button>
                                    </div>

                                    <div class="w-24 text-right">
                                        <p class="font-semibold">${{ item.subtotal.toFixed(2) }}</p>
                                    </div>
                                </div>

                                <Separator v-if="index < cartItems.length - 1" class="mt-4" />
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Order Summary -->
                <div>
                    <Card>
                        <CardHeader>
                            <CardTitle>Order Summary</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Subtotal</span>
                                    <span>${{ total.toFixed(2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Shipping</span>
                                    <span>Free</span>
                                </div>
                                <Separator />
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span>${{ total.toFixed(2) }}</span>
                                </div>
                            </div>

                            <Button @click="checkoutHandler" class="w-full" size="lg">
                                Proceed to Checkout
                            </Button>

                            <Link :href="productsRoute.index().url">
                                <Button variant="outline" class="w-full">
                                    Continue Shopping
                                </Button>
                            </Link>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
