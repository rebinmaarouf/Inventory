import axios from 'axios';

class AppStorage {
    static storeToken(token) {
        localStorage.setItem('token', token);
    }

    static getToken() {
        return localStorage.getItem('token');
    }

    static clear() {
        localStorage.removeItem('token');
    }

    static attachTokenToAxios() {
        const token = this.getToken();
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        }
    }
}

export default AppStorage;
