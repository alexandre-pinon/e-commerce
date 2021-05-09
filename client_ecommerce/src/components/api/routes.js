export const userRoutes = () => ({
  register: `/api/register`,
  login: `/api/login`,
  logout: `/api/logout`,
  info: `/api/user`,
  refreshToken: `/token/refresh`,
});
export const productRoutes = (id) => ({
  all: `/api/products`,
  single: id ? `/api/product/${id}` : `/api/product`,
});
export const cartRoutes = (id) => ({
  validate: `/api/cart/validate`,
  single: id ? `/api/cart/${id}` : `/api/cart`,
});
export const orderRoutes = (id) => ({
  all: `/api/orders`,
  single: id ? `/api/order/${id}` : `/api/order`,
});
