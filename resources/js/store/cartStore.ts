import { create } from "zustand";
import { persist } from "zustand/middleware";
import type { Product } from "@/data/products";

export interface CartItem {
  product: Product;
  quantity: number;
  selectedWeight: string;
}

interface CartStore {
  items: CartItem[];
  wishlist: number[];
  addItem: (product: Product, weight: string, qty?: number) => void;
  removeItem: (productId: number) => void;
  updateQuantity: (productId: number, qty: number) => void;
  clearCart: () => void;
  toggleWishlist: (productId: number) => void;
  totalItems: () => number;
  subtotal: () => number;
}

export const useCartStore = create<CartStore>()(
  persist(
    (set, get) => ({
      items: [],
      wishlist: [],
      addItem: (product, weight, qty = 1) => {
        const items = get().items;
        const existing = items.find((i) => i.product.id === product.id && i.selectedWeight === weight);
        if (existing) {
          set({ items: items.map((i) => i.product.id === product.id && i.selectedWeight === weight ? { ...i, quantity: i.quantity + qty } : i) });
        } else {
          set({ items: [...items, { product, quantity: qty, selectedWeight: weight }] });
        }
      },
      removeItem: (id) => set({ items: get().items.filter((i) => i.product.id !== id) }),
      updateQuantity: (id, qty) => {
        if (qty <= 0) return get().removeItem(id);
        set({ items: get().items.map((i) => (i.product.id === id ? { ...i, quantity: qty } : i)) });
      },
      clearCart: () => set({ items: [] }),
      toggleWishlist: (id) => {
        const wl = get().wishlist;
        set({ wishlist: wl.includes(id) ? wl.filter((i) => i !== id) : [...wl, id] });
      },
      totalItems: () => get().items.reduce((sum, i) => sum + i.quantity, 0),
      subtotal: () => get().items.reduce((sum, i) => sum + i.product.price * i.quantity, 0),
    }),
    { name: "organic-cart" }
  )
);
