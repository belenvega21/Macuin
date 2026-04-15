
    const API = 'http://localhost:8001';
    const JWT_TOKEN = '';
    let pedidosCache = [] || [];
    let usuariosCache = {};
    let productosCache = {};

    try {
        const initialUsers = [] || [];
        const initialProds = [] || [];
        
        if (Array.isArray(initialUsers)) {
            initialUsers.forEach(u => { if(u && u.id) usuariosCache[u.id] = u; });
        }
        if (Array.isArray(initialProds)) {
            initialProds.forEach(p => { if(p && p.id) productosCache[p.id] = p; });
        }
    } catch (e) {
        console.error('Error', e);
    }
