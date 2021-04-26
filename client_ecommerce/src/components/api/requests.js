import { userRoutes, productRoutes, cartRoutes, orderRoutes } from "./routes";

const { REACT_APP_API_ROOT_URL } = process.env;

const searchTypeToUrl = (table, type, id) => {
  const apiRoutes = {
    USER: userRoutes(id)[type],
    PRODUCT: productRoutes(id)[type],
    CART: cartRoutes(id)[type],
    ORDER: orderRoutes(id)[type],
  };
  return apiRoutes[table] ? apiRoutes[table] : null;
};

const checkIfPrivate = (method, table, type) => {
  if (table === "USER" && method === "POST") {
    if (type === "register" || type === "login") return false;
  }

  if (table === "PRODUCT" && method === "GET") return false;

  return true;
};

const commonRequest = async ({
  method,
  table,
  type,
  id = "",
  data = null,
  accessToken = null,
}) => {
  const queryIsPrivate = checkIfPrivate(method, table, type);
  const url = REACT_APP_API_ROOT_URL + searchTypeToUrl(table, type, id);
  const authorization = queryIsPrivate ? `Bearer ${accessToken}` : "";

  console.log({ url, authorization, data });

  const response = await fetch(url, {
    method: method,
    headers: {
      Authorization: authorization,
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  });
  return response.json();
};

const get = (params) => {
  return commonRequest({ ...params, method: "GET" });
};

const post = (params) => {
  return commonRequest({ ...params, method: "POST" });
};

const put = (params) => {
  return commonRequest({ ...params, method: "PUT" });
};

const remove = (params) => {
  return commonRequest({ ...params, method: "DELETE" });
};

export const API = { get, post, put, remove };
