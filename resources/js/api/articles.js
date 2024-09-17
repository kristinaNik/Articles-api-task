import axios from 'axios';
export const fetchArticles = async (page = 1, filters = {}) => {
    try {
        const params = { ...filters, page };
        const response = await axios.get('http://localhost:8000/api/articles', { params });

        return response.data;
    } catch (error) {
        throw error;
    }
};