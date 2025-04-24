class Router {
    constructor() {
        this.basePath = '/admin';
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

    addRoute(path, handlerName, customHandler = null) {
        const handler = customHandler || (() => {
            const fn = this.defaultHandlers[handlerName];
            if (typeof fn === 'function') {
                fn();
            } else {
                console.error(`Handler "${handlerName}" chưa được đăng ký hoặc không phải là function.`);
            }
        });

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
        } else {
            console.warn(`Không tìm thấy route tương ứng với path "${path}"`);
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

        // muốn thêm router nào thì thêm ở đây rồi vô file js tạo đăng kí handler tương ứng, ex: file product.js
        this.addRoute('/', 'handleHome');
        this.addRoute('/products', 'handleProduct');
        this.addRoute('/bills', 'handleBill');
        this.addRoute('/goods-receipts', 'handleGoodsReceipt');
        this.addRoute('/products/add', 'handleAddProduct');
        this.addRoute('/products/edit','handleEditProduct')
        this.addRoute('/products/delete','handleDeleteProduct')
        this.addRoute('/products/search','handleSearch');
        // Nếu path không bắt đầu bằng /admin/, điều hướng về /
        if (!window.location.pathname.startsWith(this.basePath + '/')) {
            history.replaceState({}, '', this.basePath + '/');
        }
        this.handleNavigation();
    }
}
const router = new Router();
window.router = router;
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => router.init());
} else {
    router.init();
}
