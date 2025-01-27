const Ziggy = {
  url: "http://localhost:8000",
  port: 8000,
  defaults: {},
  routes: {
      "passport.token": {
          uri: "oauth/token",
          methods: ["POST"],
      },
      "passport.authorizations.authorize": {
          uri: "oauth/authorize",
          methods: ["GET", "HEAD"],
      },
      "passport.token.refresh": {
          uri: "oauth/token/refresh",
          methods: ["POST"],
      },
      "passport.authorizations.approve": {
          uri: "oauth/authorize",
          methods: ["POST"],
      },
      "passport.authorizations.deny": {
          uri: "oauth/authorize",
          methods: ["DELETE"],
      },
      "passport.tokens.index": {
          uri: "oauth/tokens",
          methods: ["GET", "HEAD"],
      },
      "passport.tokens.destroy": {
          uri: "oauth/tokens/{token_id}",
          methods: ["DELETE"],
          parameters: ["token_id"],
      },
      "passport.clients.index": {
          uri: "oauth/clients",
          methods: ["GET", "HEAD"],
      },
      "passport.clients.store": {
          uri: "oauth/clients",
          methods: ["POST"],
      },
      "passport.clients.update": {
          uri: "oauth/clients/{client_id}",
          methods: ["PUT"],
          parameters: ["client_id"],
      },
      "passport.clients.destroy": {
          uri: "oauth/clients/{client_id}",
          methods: ["DELETE"],
          parameters: ["client_id"],
      },
      "passport.scopes.index": {
          uri: "oauth/scopes",
          methods: ["GET", "HEAD"],
      },
      "passport.personal.tokens.index": {
          uri: "oauth/personal-access-tokens",
          methods: ["GET", "HEAD"],
      },
      "passport.personal.tokens.store": {
          uri: "oauth/personal-access-tokens",
          methods: ["POST"],
      },
      "passport.personal.tokens.destroy": {
          uri: "oauth/personal-access-tokens/{token_id}",
          methods: ["DELETE"],
          parameters: ["token_id"],
      },
      "sanctum.csrf-cookie": {
          uri: "sanctum/csrf-cookie",
          methods: ["GET", "HEAD"],
      },
      "password.email": {
          uri: "api/auth/forgot-password",
          methods: ["POST"],
      },
      "password.update": {
          uri: "api/auth/reset-password",
          methods: ["POST"],
      },
      "socialite.callback": {
          uri: "auth/{provider}/callback",
          methods: ["GET", "HEAD"],
          parameters: ["provider"],
      },
      "password.reset": {
          uri: "reset-password/{token}",
          methods: ["GET", "HEAD"],
          parameters: ["token"],
      },
      "socialite.auth": {
          uri: "auth/{provider}/redirect",
          methods: ["GET", "HEAD"],
          parameters: ["provider"],
      },
      "storage.local": {
          uri: "storage/{path}",
          methods: ["GET", "HEAD"],
          wheres: {
              path: ".*",
          },
          parameters: ["path"],
      },
  },
};

if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy }; // Export the Ziggy object directly