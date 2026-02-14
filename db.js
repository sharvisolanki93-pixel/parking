// Initialize Data if it doesn't exist
const initialData = {
    users: [{ id: 1, name: "Admin", email: "admin@park.com", password: "admin", role: "admin" }],
    slots: [
        { id: 1, number: "A01", type: "car", status: "available", rate: 50 },
        { id: 2, number: "A02", type: "car", status: "available", rate: 50 },
        { id: 3, number: "A03", type: "car", status: "available", rate: 50 },
        { id: 4, number: "A04", type: "car", status: "available", rate: 50 },
        { id: 5, number: "A05", type: "car", status: "available", rate: 50 },
        { id: 6, number: "B01", type: "bike", status: "available", rate: 20 },
        { id: 7, number: "B02", type: "bike", status: "available", rate: 20 },
    ],
    bookings: []
};

if (!localStorage.getItem('parkData')) {
    localStorage.setItem('parkData', JSON.stringify(initialData));
}

const DB = {
    getData: () => JSON.parse(localStorage.getItem('parkData')),
    saveData: (data) => localStorage.setItem('parkData', JSON.stringify(data)),
    
    login: (email, password) => {
        const data = DB.getData();
        const user = data.users.find(u => u.email === email && u.password === password);
        if (user) {
            sessionStorage.setItem('user', JSON.stringify(user));
            return user;
        }
        return null;
    },
    
    register: (name, email, password) => {
        const data = DB.getData();
        if (data.users.find(u => u.email === email)) return false;
        const newUser = { id: Date.now(), name, email, password, role: 'user' };
        data.users.push(newUser);
        DB.saveData(data);
        return true;
    },

    bookSlot: (slotId, vehicleNumber) => {
        const data = DB.getData();
        const user = JSON.parse(sessionStorage.getItem('user'));
        const slot = data.slots.find(s => s.id == slotId);
        
        if (slot && slot.status === 'available') {
            slot.status = 'occupied';
            const booking = {
                id: Date.now(),
                userId: user.id,
                slotId: slot.id,
                slotNumber: slot.number,
                vehicleNumber,
                entryTime: new Date().toLocaleString(),
                status: 'active'
            };
            data.bookings.push(booking);
            DB.saveData(data);
            return true;
        }
        return false;
    }
};
