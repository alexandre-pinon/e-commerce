export const userRoutes = () => ({
  register: `/api/register`,
  login: `/api/login`,
  logout: `/api/logout`,
  info: `/api/user`,
  refreshToken: `/token/refresh`,
});
export const productRoutes = (id) => ({
  all: `/api/products`,
  single: `/api/product/${id}`,
});
export const cartRoutes = (id) => ({
  validate: `/api/cart/validate`,
  single: `/api/cart/${id}`,
});
export const orderRoutes = (id) => ({
  all: `/api/orders`,
  single: `/api/orders/${id}`,
});
