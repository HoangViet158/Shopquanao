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
        this.isNavigating = false;
        this.currentPath = '';
    }

    registerHandler(name, handler) {
        if (typeof handler === 'function') {
            this.defaultHandlers[name] = handler;
        } else {
            console.error(`Handler "${name}" must be a function`);
        }
    }

    addRoute(path, handlerName, customHandler = null) {
        const handler = customHandler || ((params) => {
            const fn = this.defaultHandlers[handlerName];
            if (typeof fn === 'function') {
                fn(params);
            } else {
                console.error(`Handler "${handlerName}" is not registered or not a function`);
                this.navigate('/'); // Fallback to home if handler is invalid
            }
        });

        const regexPath = path.replace(/:\w+/g, '([^/]+)') + '(?:\\?([^#]*))?$';
        
        this.routes.push({
            path,
            handler,
            regex: new RegExp(`^${regexPath}`)
        });
    }

    handleNavigation() {
        if (this.isNavigating) return;
        this.isNavigating = true;

        const path = window.location.pathname.replace(this.basePath, '') || '/';
        const search = window.location.search;
        this.currentPath = path + search;

        try {
            let matchedRoute = null;
            let matchResult = null;

            // Find matching route
            for (const route of this.routes) {
                matchResult = path.match(route.regex);
                if (matchResult) {
                    matchedRoute = route;
                    break;
                }
            }

            if (matchedRoute) {
                const params = this.extractParams(matchedRoute.path, path);
               
                if (search) {
                    const searchParams = new URLSearchParams(search);
                    for (const [key, value] of searchParams.entries()) {
                        params[key] = value;
                    }
                }
    
                matchedRoute.handler(params);
            } else {
                console.warn(`No route found for path "${path}"`);
                if (path !== '/') {
                    this.navigate('/', {}, true);
                }
            }
        } catch (error) {
            console.error('Navigation error:', error);
            this.navigate('/', {}, true);
        } finally {
            this.isNavigating = false;
        }
    }

    extractParams(routePath, currentPath) {
        const params = {};
        const routeParts = routePath.split('/');
        const pathParts = currentPath.split('/');

        for (let i = 0; i < routeParts.length; i++) {
            const part = routeParts[i];
            if (part.startsWith(':')) {
                params[part.substring(1)] = decodeURIComponent(pathParts[i]);
            }
        }

        return params;
    }

    navigate(path, queryParams = {}, replace = false) {
        // Build query string
        const queryString = Object.entries(queryParams)
            .filter(([_, value]) => value !== undefined && value !== null)
            .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
            .join('&');
        let fullPath = this.basePath + path;
        if (queryString) {
            fullPath += `?${queryString}`;
        }

        if (window.location.pathname + window.location.search === fullPath) {
            return;
        }

        if (replace) {
            history.replaceState({}, '', fullPath);
        } else {
            history.pushState({}, '', fullPath);
        }
        this.handleNavigation();
    }

    registerGlobalHandlers() {
        const handlers = [
            'handleProduct', 'handleBill', 'handleGoodsReceipt',
            'handleAddProduct', 'handleEditProduct', 
            'handleDeleteProduct', 'handleSearch',
            'handleAddGoodsReceipt', 'handleDetailGoodsReceipt',
            'handleSearchGoodsReceipt','handleBillSearch','handleBillDetail',
            'handleBillStatus'
        ];

        handlers.forEach(name => {
            if (window[name] && typeof window[name] === 'function') {
                this.registerHandler(name, window[name]);
            }
        });
    }
    // muốn thêm router thì làm các bước sau:
    // 1. thêm route vào router.js trong hàm init() với cú pháp: this.addRoute('/path', 'handleName');
    // 2. thêm hàm xử lý vào trong file js tương ứng với route đó
    // 3. thêm hàm xử lý vào trong hàm registerGlobalHandlers() 
    init() {
        this.registerGlobalHandlers();
        window.addEventListener('popstate', () => {
            if (window.location.pathname + window.location.search !== this.currentPath) {
                this.handleNavigation();
            }
        });

        // Register routes
        this.addRoute('/', 'handleHome');
        this.addRoute('/products', 'handleProduct');
        this.addRoute('/products/add', 'handleAddProduct');
        this.addRoute('/products/edit/:id', 'handleEditProduct');
        this.addRoute('/products/delete/:id', 'handleDeleteProduct');
        this.addRoute('/products/search', 'handleSearch');
        this.addRoute('/bills', 'handleBill');
        this.addRoute('/bills/detail/:id','handleBillDetail');
        this.addRoute('/bills/updateStatus/:id','handleBillStatus');
        this.addRoute('bills/search','handleBillSearch');
        this.addRoute('/goods-receipts', 'handleGoodsReceipt');
        this.addRoute('/goods-receipts/add', 'handleAddGoodsReceipt');
        this.addRoute('/goods-receipts/detail/:id', 'handleDetailGoodsReceipt');
        this.addRoute('/goods-receipts/search', 'handleSearchGoodsReceipt');
        if (!window.location.pathname.startsWith(this.basePath + '/')) {
            this.navigate('/', {}, true);
            return;
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