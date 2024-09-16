import axios from 'axios';

const API_URL = 'http://localhost:8000/api/articles';

export const fetchArticles = async () => {
    try {
        const response = await axios.get(API_URL);
        return response.data;
    } catch (error) {
        console.error('Error fetching articles:', error);
        throw error;
    }
};