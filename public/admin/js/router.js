class Router {
    constructor() {
        this.basePath = '/admin'; // Đã điều chỉnh cho localhost
        this.routes = [];
        this.defaultHandlers = {
            handleHome: () => {
                document.querySelector('.Mange_client').innerHTML = `
                    <div class="admin_home">
                        <h4>Chào Mừng Đến Với Trang Quản Trị</h4>
                    </div>
                `;
            }
        };
    }

    registerHandler(name, handler) {
        this.defaultHandlers[name] = handler;
    }

    addRoute(path, handlerName, customHandler) {
        const handler = customHandler || (() => this.defaultHandlers[handlerName]());
        this.routes.push({ 
            path, 
            handler,
            regex: new RegExp(`^${path.replace(/:\w+/g, '([^/]+)')}$`)
        });
    }

    handleNavigation() {
        const path = window.location.pathname.replace(this.basePath, '') || '/';
        
        const matchedRoute = this.routes.find(route => route.regex.test(path));
        if (matchedRoute) {
            const params = this.extractParams(matchedRoute.path, path);
            matchedRoute.handler(params);
        }
    }

    extractParams(routePath, currentPath) {
        const params = {};
        const routeParts = routePath.split('/');
        const pathParts = currentPath.split('/');

        routeParts.forEach((part, i) => {
            if (part.startsWith(':')) {
                params[part.substring(1)] = pathParts[i];
            }
        });

        return params;
    }

    navigate(path) {
        history.pushState({}, '', this.basePath + path);
        this.handleNavigation();
    }

    init() {
        window.addEventListener('popstate', () => this.handleNavigation());
        
        // Đăng ký routes cơ bản
        this.addRoute('/', 'handleHome');
        this.addRoute('/products', 'handleProduct');
        this.addRoute('/bills', 'handleBill');
        this.addRoute('/goods-receipts', 'handleGoodsReceipt');
        this.addRoute('/bills/:id', null, (params) => {
            this.defaultHandlers.handleBill?.();
            window.showBillDetail?.(params.id);
            $('#billDetailModal').modal('show');
        });

        // Xử lý URL ban đầu
        if (!window.location.pathname.startsWith(this.basePath + '/')) {
            history.replaceState({}, '', this.basePath + '/');
        }
        this.handleNavigation();
    }
}

// Khởi tạo router ngay khi load
const router = new Router();
window.router = router;

// Khởi động router khi DOM sẵn sàng
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => router.init());
} else {
    router.init();
}