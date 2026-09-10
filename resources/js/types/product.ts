export type ProductCategory = {
    id: number;
    name: string;
    slug: string;
};

export type Product = {
    id: number;
    name: string;
    slug: string;
    description: string;
    price: number;
    stock: number;
    image: string | null;
    category: ProductCategory;
};

export type ProductFilters = {
    q: string;
    category: string;
    sort: 'newest' | 'price_asc' | 'price_desc';
};

export type PaginatedProducts = {
    data: Product[];
    current_page: number;
    last_page: number;
    total: number;
    from: number | null;
    to: number | null;
    prev_page_url: string | null;
    next_page_url: string | null;
};

export type CartItem = {
    product: Product;
    quantity: number;
    subtotal: number;
};

export type Cart = {
    items: CartItem[];
    total: number;
    quantity: number;
};

export type OrderItem = {
    id: number;
    product_name: string;
    quantity: number;
    price: number;
};

export type Order = {
    id: number;
    total_price: number;
    customer_name: string;
    postal_code: string;
    address: string;
    items: OrderItem[];
};

export type OrderHistory = {
    id: number;
    total_price: number;
    status: string;
    items_count: number;
    created_at: string;
};
