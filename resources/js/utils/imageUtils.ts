import { productImageMap } from "@/data/productImages";

/**
 * Extract image path from various backend formats
 */
const extractImagePath = (imageData: any): string | null => {
  if (!imageData) return null;
  
  // Already a string path
  if (typeof imageData === 'string' && imageData !== "/placeholder.svg") {
    return imageData;
  }
  
  // Array of images - get first one
  if (Array.isArray(imageData) && imageData.length > 0) {
    return extractImagePath(imageData[0]); // Recursive call to handle nested objects
  }
  
  // Object with image path - ProductImage model from Laravel: {id, image, product_id, ...}
  if (typeof imageData === 'object' && imageData !== null) {
    // Priority order for extracting path
    const path = imageData.image || imageData.url || imageData.path || imageData.src;
    if (typeof path === 'string') {
      return path;
    }
  }
  
  return null;
};

/**
 * Normalize image path from backend formats to proper URL
 */
const normalizeImagePath = (path: string): string => {
  if (!path || typeof path !== 'string') return '';
  
  // Remove double slashes except after protocol
  let normalized = path.replace(/([^:])\/\//g, '$1/');
  
  // Convert "public/uploads/..." to "/uploads/..." (files are in public folder)
  if (normalized.startsWith('public/uploads/')) {
    normalized = '/' + normalized.slice(7); // Remove "public/" prefix
  }
  // Handle paths that just start with "uploads/"
  else if (normalized.startsWith('uploads/')) {
    normalized = '/' + normalized;
  }
  
  return normalized;
};

/**
 * Get the appropriate image source for a product
 * Priority: backend image URL > static map > placeholder
 */
export const getProductImageSrc = (product: { slug?: string; image?: any }): string => {
  if (!product) return "/placeholder.svg";
  
  // Extract image path from various formats
  const rawPath = extractImagePath(product.image);
  
  // Check if we have a valid backend URL
  if (rawPath) {
    // Normalize the path
    const imageUrl = normalizeImagePath(rawPath);
    
    if (imageUrl.startsWith("http") || imageUrl.startsWith("/storage") || imageUrl.startsWith("/uploads")) {
      return imageUrl;
    }
  }
  
  // Try static product image map
  if (product.slug && productImageMap[product.slug]) {
    return productImageMap[product.slug];
  }
  
  // Fallback to placeholder
  return "/placeholder.svg";
};
