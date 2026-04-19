const STORAGE_KEYS = {
  treks: "rtt_admin_treks",
  blogs: "rtt_admin_blogs",
  quotations: "rtt_admin_quotations",
  rentalLeads: "rtt_admin_rental_leads",
  hotelLeads: "rtt_admin_hotel_leads",
};

const state = {
  treks: loadItems(STORAGE_KEYS.treks),
  blogs: loadItems(STORAGE_KEYS.blogs),
  quotations: loadItems(STORAGE_KEYS.quotations),
  rentalLeads: loadItems(STORAGE_KEYS.rentalLeads),
  hotelLeads: loadItems(STORAGE_KEYS.hotelLeads),
};

const DEMO_TREKS = [
  {
    id: "demo-trek-1",
    title: "Kedarnath Spiritual Trek",
    category: "Domestic",
    region: "Uttarakhand",
    trekType: "Pilgrimage",
    duration: "6 Days / 5 Nights",
    altitude: "3584",
    difficulty: "Moderate",
    price: "14999",
    image:
      "https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=80",
    description:
      "Sacred Himalayan trail with scenic camps, guided support, and temple darshan experience.",
    slug: "kedarnath-spiritual-trek",
    canonicalUrl: "https://rudraanshtours.com/treks/kedarnath-spiritual-trek",
    metaTitle: "Kedarnath Spiritual Trek 2026",
    metaDescription:
      "Book Kedarnath trek with certified trek leaders, meals, and stay included.",
    overview:
      "A sacred Himalayan journey with guided support, temple darshan, and scenic camps along the route.",
    overviewPdfName: "kedarnath-overview.pdf",
    faq: [
      {
        question: "Is prior trekking experience required?",
        answer: "No, but basic fitness is recommended for this trek.",
      },
      {
        question: "Are permits included?",
        answer: "Yes, required trek permits are included in the package.",
      },
    ],
    inclusions: "Accommodation, breakfast and dinner, trek leader, permits.",
    exclusions:
      "Any personal shopping, travel insurance, emergency evacuation.",
    itinerary: [
      {
        day: "Day 1",
        title: "Arrival in Guptkashi",
        details:
          "Reach the base point, check-in, and briefing with the trek leader.",
      },
      {
        day: "Day 2",
        title: "Drive to Sonprayag",
        details: "Local transfer, permits check, and prepare for the trek.",
      },
      {
        day: "Day 3",
        title: "Trek to Kedarnath",
        details: "Full-day trek with evening darshan and camp stay.",
      },
    ],
    keywords: "kedarnath trek, uttarakhand trek, spiritual trek",
    ogTitle: "Kedarnath Spiritual Trek - Rudraansh Tours & Travel",
    ogDescription:
      "Book the Kedarnath spiritual trek with guided support, scenic camps, and temple darshan.",
    ogImage:
      "https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=80",
    createdAt: Date.now() - 3000,
  },
  {
    id: "demo-trek-2",
    title: "Hampta Pass Adventure",
    category: "Domestic",
    region: "Himachal Pradesh",
    trekType: "Adventure",
    duration: "5 Days / 4 Nights",
    altitude: "4270",
    difficulty: "Moderate",
    price: "12999",
    image:
      "https://images.unsplash.com/photo-1454496522488-7a8e488e8606?auto=format&fit=crop&w=1200&q=80",
    description:
      "Cross dramatic valleys and high mountain passes with expert local support team.",
    slug: "hampta-pass-adventure",
    canonicalUrl: "https://rudraanshtours.com/treks/hampta-pass-adventure",
    metaTitle: "Hampta Pass Adventure Trek",
    metaDescription:
      "Experience the iconic Hampta Pass route with guided camps and logistics.",
    overview:
      "A scenic crossover trek through valleys, glaciers, and alpine landscapes with expert support.",
    overviewPdfName: "hampta-pass-overview.pdf",
    faq: [
      {
        question: "What is the best month for Hampta Pass?",
        answer: "June to September is the ideal trekking window.",
      },
      {
        question: "Will there be network coverage?",
        answer: "Coverage is patchy, mostly unavailable on high camps.",
      },
    ],
    inclusions: "Tents, meals during trek, local transfers, guide support.",
    exclusions: "Backpack offloading, personal medication, snacks.",
    itinerary: [
      {
        day: "Day 1",
        title: "Drive to Jobra",
        details: "Reach base camp and acclimatize before the trek.",
      },
      {
        day: "Day 2",
        title: "Trek to Balu Ka Ghera",
        details: "Cross meadows and river crossings with guided support.",
      },
      {
        day: "Day 3",
        title: "Hampta Pass Crossing",
        details: "Summit day and descend toward the scenic valley.",
      },
    ],
    keywords: "hampta pass, himachal trek, mountain adventure",
    ogTitle: "Hampta Pass Adventure - Rudraansh Tours & Travel",
    ogDescription:
      "Explore the Hampta Pass trail with mountain views, expert support, and smooth logistics.",
    ogImage:
      "https://images.unsplash.com/photo-1454496522488-7a8e488e8606?auto=format&fit=crop&w=1200&q=80",
    createdAt: Date.now() - 2000,
  },
  {
    id: "demo-trek-3",
    title: "Everest Base Camp Lite",
    category: "International",
    region: "Solukhumbu",
    trekType: "Most Popular",
    duration: "10 Days / 9 Nights",
    altitude: "5364",
    difficulty: "Hard",
    price: "48999",
    image:
      "https://images.unsplash.com/photo-1454496522488-7a8e488e8606?auto=format&fit=crop&w=1200&q=80",
    description:
      "High-altitude Nepal expedition route designed for experienced and fit trekkers.",
    slug: "everest-base-camp-lite",
    canonicalUrl: "https://rudraanshtours.com/treks/everest-base-camp-lite",
    metaTitle: "Everest Base Camp Lite Package",
    metaDescription:
      "Join our guided Everest Base Camp style trek with safety-first planning.",
    overview:
      "A high-altitude trekking experience crafted for fit and experienced adventurers.",
    overviewPdfName: "everest-base-camp-lite.pdf",
    faq: [
      {
        question: "Do I need travel insurance?",
        answer: "Yes, high-altitude travel insurance is strongly advised.",
      },
      {
        question: "Is acclimatization planned?",
        answer: "Yes, the itinerary includes acclimatization stops.",
      },
    ],
    inclusions: "Guide support, accommodation, expedition logistics.",
    exclusions: "International flights, visa fees, personal climbing gear.",
    itinerary: [
      {
        day: "Day 1",
        title: "Fly to Lukla",
        details: "Begin the expedition from the mountain gateway.",
      },
      {
        day: "Day 2",
        title: "Trek to Namche Bazaar",
        details: "Acclimatization and village exploration.",
      },
      {
        day: "Day 3",
        title: "Move towards Tengboche",
        details: "Forest trails and Himalayan views.",
      },
    ],
    keywords: "everest base camp, nepal trek, international trekking",
    ogTitle: "Everest Base Camp Lite - Rudraansh Tours & Travel",
    ogDescription:
      "High-altitude adventure route for experienced trekkers with safety-first planning.",
    ogImage:
      "https://images.unsplash.com/photo-1454496522488-7a8e488e8606?auto=format&fit=crop&w=1200&q=80",
    createdAt: Date.now() - 1000,
  },
];

const DEMO_QUOTATIONS = [
  {
    id: "demo-quote-1",
    customerName: "Rahul Verma",
    phone: "+91 98765 43210",
    email: "rahul.verma@email.com",
    destination: "Kedarnath Trek",
    travelDate: "2026-06-18",
    travelers: "4",
    budget: "55000",
    status: "New Lead",
    message: "Family trip plan karna hai with comfortable stay and transport.",
    createdAt: Date.now() - 1500,
  },
  {
    id: "demo-quote-2",
    customerName: "Priya Sharma",
    phone: "+91 99887 77665",
    email: "priya.sharma@email.com",
    destination: "Hampta Pass Trek",
    travelDate: "2026-07-05",
    travelers: "2",
    budget: "32000",
    status: "Contacted",
    message:
      "Couple trek package chahiye, guide aur permits included hone chahiye.",
    createdAt: Date.now() - 1200,
  },
  {
    id: "demo-quote-3",
    customerName: "Amit Singh",
    phone: "+91 91234 56789",
    email: "amit.singh@email.com",
    destination: "Valley of Flowers",
    travelDate: "2026-08-11",
    travelers: "6",
    budget: "78000",
    status: "Quoted",
    message:
      "Group booking hai, pickup Haridwar se chahiye aur meals included ho.",
    createdAt: Date.now() - 900,
  },
];

const QUOTATION_STATUSES = ["New Lead", "Contacted", "Quoted", "Closed"];

const DEMO_RENTAL_LEADS = [
  {
    id: "demo-rental-1",
    customerName: "Nisha Gupta",
    phone: "+91 90011 22334",
    email: "nisha.gupta@email.com",
    carType: "SUV",
    route: "Delhi to Manali",
    pickupDate: "2026-05-14",
    travelers: "5",
    budget: "18000",
    status: "New Lead",
    message: "3 day trip ke liye driver ke sath SUV chahiye.",
    createdAt: Date.now() - 700,
  },
  {
    id: "demo-rental-2",
    customerName: "Karan Malhotra",
    phone: "+91 95566 77889",
    email: "karan.malhotra@email.com",
    carType: "Innova Crysta",
    route: "Dehradun Airport to Kedarnath base",
    pickupDate: "2026-05-22",
    travelers: "6",
    budget: "32000",
    status: "Contacted",
    message: "Pickup timing early morning rahega, luggage bhi kaafi hoga.",
    createdAt: Date.now() - 500,
  },
];

const DEMO_HOTEL_LEADS = [
  {
    id: "demo-hotel-1",
    customerName: "Sneha Arora",
    phone: "+91 98900 11223",
    email: "sneha.arora@email.com",
    city: "Manali",
    checkInDate: "2026-06-03",
    checkOutDate: "2026-06-06",
    rooms: "2",
    guests: "4",
    budget: "24000",
    status: "New Lead",
    message: "Near mall road family-friendly hotel chahiye with breakfast.",
    createdAt: Date.now() - 650,
  },
  {
    id: "demo-hotel-2",
    customerName: "Rohit Jain",
    phone: "+91 97123 44556",
    email: "rohit.jain@email.com",
    city: "Rishikesh",
    checkInDate: "2026-06-12",
    checkOutDate: "2026-06-15",
    rooms: "3",
    guests: "7",
    budget: "36000",
    status: "Quoted",
    message: "Group ke liye river-view property preference hai.",
    createdAt: Date.now() - 450,
  },
];

function seedDemoTreks() {
  if (state.treks.length > 0) return;
  state.treks = [...DEMO_TREKS];
  saveItems();
}

function seedDemoQuotations() {
  if (state.quotations.length > 0) return;
  state.quotations = [...DEMO_QUOTATIONS];
  saveItems();
}

function seedDemoRentalLeads() {
  if (state.rentalLeads.length > 0) return;
  state.rentalLeads = [...DEMO_RENTAL_LEADS];
  saveItems();
}

function seedDemoHotelLeads() {
  if (state.hotelLeads.length > 0) return;
  state.hotelLeads = [...DEMO_HOTEL_LEADS];
  saveItems();
}

function loadItems(key) {
  try {
    const raw = localStorage.getItem(key);
    return raw ? JSON.parse(raw) : [];
  } catch {
    return [];
  }
}

function saveItems() {
  localStorage.setItem(STORAGE_KEYS.treks, JSON.stringify(state.treks));
  localStorage.setItem(STORAGE_KEYS.blogs, JSON.stringify(state.blogs));
  localStorage.setItem(
    STORAGE_KEYS.quotations,
    JSON.stringify(state.quotations),
  );
  localStorage.setItem(
    STORAGE_KEYS.rentalLeads,
    JSON.stringify(state.rentalLeads),
  );
  localStorage.setItem(
    STORAGE_KEYS.hotelLeads,
    JSON.stringify(state.hotelLeads),
  );
}

function setupLogout() {
  // Logout is handled by logout.php link in sidebar.
}

function setupResponsiveSidebar() {
  const sidebar = document.querySelector(".sidebar");
  if (!(sidebar instanceof HTMLElement)) return;

  const mediaQuery = window.matchMedia("(max-width: 980px)");
  let toggleButton = document.getElementById("mobile-menu-trigger");
  if (!(toggleButton instanceof HTMLButtonElement)) {
    toggleButton = document.createElement("button");
    toggleButton.type = "button";
    toggleButton.className = "mobile-menu-trigger";
    toggleButton.id = "mobile-menu-trigger";
    toggleButton.setAttribute("aria-label", "Toggle sidebar menu");
    toggleButton.setAttribute("aria-controls", "admin-nav");
    toggleButton.innerHTML = "<span></span><span></span><span></span>";
    document.body.append(toggleButton);
  }

  let backdrop = document.querySelector(".sidebar-backdrop");
  if (!(backdrop instanceof HTMLElement)) {
    backdrop = document.createElement("div");
    backdrop.className = "sidebar-backdrop";
    document.body.append(backdrop);
  }

  function setMenuState(expanded) {
    sidebar.classList.toggle("mobile-expanded", expanded);
    sidebar.classList.toggle("mobile-collapsed", !expanded);
    toggleButton.classList.toggle("is-open", expanded);
    toggleButton.setAttribute("aria-expanded", expanded ? "true" : "false");
    document.body.classList.toggle("menu-open", expanded);
  }

  function syncSidebarMode() {
    if (mediaQuery.matches) {
      setMenuState(false);
      return;
    }

    sidebar.classList.remove("mobile-collapsed", "mobile-expanded");
    toggleButton.classList.remove("is-open");
    toggleButton.setAttribute("aria-expanded", "false");
    document.body.classList.remove("menu-open");
  }

  toggleButton.addEventListener("click", () => {
    if (!mediaQuery.matches) return;
    const isOpen = sidebar.classList.contains("mobile-expanded");
    setMenuState(!isOpen);
  });

  backdrop.addEventListener("click", () => {
    if (!mediaQuery.matches) return;
    setMenuState(false);
  });

  const nav = sidebar.querySelector(".nav-pills");
  if (nav instanceof HTMLElement) {
    nav.id = "admin-nav";
    nav.addEventListener("click", (event) => {
      const target = event.target;
      if (
        !mediaQuery.matches ||
        !(target instanceof HTMLElement) ||
        !target.closest("a")
      )
        return;
      setMenuState(false);
    });
  }

  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape" || !mediaQuery.matches) return;
    setMenuState(false);
  });

  if (typeof mediaQuery.addEventListener === "function") {
    mediaQuery.addEventListener("change", syncSidebarMode);
  } else if (typeof mediaQuery.addListener === "function") {
    mediaQuery.addListener(syncSidebarMode);
  }

  syncSidebarMode();
}

function enforceAuth(page) {
  // Access control is enforced in PHP files using session checks.
  return true;
}

function readFormData(form, fields) {
  const formData = new FormData(form);
  const entry = {};
  fields.forEach((field) => {
    entry[field] = formData.get(field)?.toString().trim() || "";
  });
  return entry;
}

function readFileAsDataUrl(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => resolve(reader.result);
    reader.onerror = () => reject(new Error("Unable to read file."));
    reader.readAsDataURL(file);
  });
}

function escapeHtml(value) {
  return value
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/\"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

function getOverviewPlainText(html) {
  const tempElement = document.createElement("div");
  tempElement.innerHTML = html || "";
  return tempElement.textContent || "";
}

function fillForm(form, item, fields) {
  fields.forEach((field) => {
    const input = form.elements.namedItem(field);
    if (
      input instanceof HTMLInputElement ||
      input instanceof HTMLTextAreaElement ||
      input instanceof HTMLSelectElement
    ) {
      const value = item[field];
      input.value =
        Array.isArray(value) || (value && typeof value === "object")
          ? JSON.stringify(value)
          : value || "";
    }
  });
}

function clearForm(
  form,
  titleElement,
  defaultTitle,
  submitButton,
  submitLabel,
) {
  form.reset();
  const idField = form.elements.namedItem("id");
  if (idField instanceof HTMLInputElement) {
    idField.value = "";
  }
  if (titleElement) titleElement.textContent = defaultTitle;
  if (submitButton) submitButton.textContent = submitLabel;
}

function renderDashboard() {
  const trekCount = document.getElementById("dashboard-trek-count");
  const blogCount = document.getElementById("dashboard-blog-count");
  const quotationCount = document.getElementById("dashboard-quotation-count");
  const latestCustomer = document.getElementById("dashboard-latest-customer");
  const latestRental = document.getElementById("dashboard-latest-rental");
  const latestHotel = document.getElementById("dashboard-latest-hotel");
  const latestTrek = document.getElementById("dashboard-latest-trek");
  const latestBlog = document.getElementById("dashboard-latest-blog");

  if (trekCount) trekCount.textContent = String(state.treks.length);
  if (blogCount) blogCount.textContent = String(state.blogs.length);
  if (quotationCount)
    quotationCount.textContent = String(state.quotations.length);

  if (latestCustomer) {
    const lead = state.quotations[0];
    latestCustomer.textContent = lead
      ? `${lead.customerName} (${lead.phone})`
      : "No submission yet.";
  }

  if (latestRental) {
    const lead = state.rentalLeads[0];
    latestRental.textContent = lead
      ? `${lead.customerName} (${lead.phone}) - ${lead.carType}`
      : "No car rental lead yet.";
  }

  if (latestHotel) {
    const lead = state.hotelLeads[0];
    latestHotel.textContent = lead
      ? `${lead.customerName} (${lead.phone}) - ${lead.city}`
      : "No hotel lead yet.";
  }

  if (latestTrek) {
    const trek = state.treks[0];
    latestTrek.textContent = trek
      ? `${trek.title} - ${trek.region}`
      : "No trek uploaded yet.";
  }

  if (latestBlog) {
    const blog = state.blogs[0];
    latestBlog.textContent = blog
      ? `${blog.title} - ${blog.category}`
      : "No blog uploaded yet.";
  }
}

async function fetchTodaysLeads() {
  try {
    console.log('Fetching today\'s leads...');
    const response = await fetch('../backend/get-todays-leads.php');
    console.log('Response status:', response.status);
    
    if (!response.ok) {
      console.error('Response not ok:', response.status, response.statusText);
      return;
    }
    
    const data = await response.json();
    console.log('Data received:', data);

    if (!data.success) {
      console.error('Error fetching today\'s leads:', data.error);
      return;
    }

    // Initialize counts
    let trekkingCount = 0;
    let hotelCount = 0;
    let rentalCount = 0;

    // Process today's leads
    if (data.todaysLeads && Array.isArray(data.todaysLeads)) {
      data.todaysLeads.forEach(lead => {
        if (lead.trip_type === 'trekking') trekkingCount = parseInt(lead.count);
        else if (lead.trip_type === 'hotel') hotelCount = parseInt(lead.count);
        else if (lead.trip_type === 'rental') rentalCount = parseInt(lead.count);
      });
    }

    console.log('Counts:', { trekkingCount, hotelCount, rentalCount });

    // Update UI
    const trekkingCountElem = document.getElementById('today-trekking-count');
    const hotelCountElem = document.getElementById('today-hotel-count');
    const rentalCountElem = document.getElementById('today-rental-count');
    const totalCountElem = document.getElementById('today-total-count');
    const dateElem = document.getElementById('today-date');

    const trekkingLeadElem = document.getElementById('today-trekking-lead');
    const hotelLeadElem = document.getElementById('today-hotel-lead');
    const rentalLeadElem = document.getElementById('today-rental-lead');

    console.log('Elements found:', {
      trekkingCountElem: !!trekkingCountElem,
      hotelCountElem: !!hotelCountElem,
      rentalCountElem: !!rentalCountElem
    });

    if (trekkingCountElem) trekkingCountElem.textContent = trekkingCount;
    if (hotelCountElem) hotelCountElem.textContent = hotelCount;
    if (rentalCountElem) rentalCountElem.textContent = rentalCount;
    if (totalCountElem) totalCountElem.textContent = (trekkingCount + hotelCount + rentalCount);

    if (dateElem) {
      const today = new Date(data.date);
      dateElem.textContent = today.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    // Show latest leads
    if (trekkingLeadElem && data.latestTrekking) {
      trekkingLeadElem.textContent = `${data.latestTrekking.full_name}`;
    } else if (trekkingLeadElem) {
      trekkingLeadElem.textContent = 'No leads yet';
    }

    if (hotelLeadElem && data.latestHotel) {
      hotelLeadElem.textContent = `${data.latestHotel.full_name}`;
    } else if (hotelLeadElem) {
      hotelLeadElem.textContent = 'No leads yet';
    }

    if (rentalLeadElem && data.latestRental) {
      rentalLeadElem.textContent = `${data.latestRental.full_name}`;
    } else if (rentalLeadElem) {
      rentalLeadElem.textContent = 'No leads yet';
    }

    console.log('Today\'s leads updated successfully');

  } catch (error) {
    console.error('Error fetching today\'s leads:', error);
  }
}

function setupTreksPage() {
  const form = document.getElementById("trek-form");
  const list = document.getElementById("trek-list");
  const formTitle = document.getElementById("trek-form-title");
  const submitButton = document.getElementById("trek-submit-btn");
  const cancelButton = document.getElementById("trek-cancel-btn");
  const stepIndicators = Array.from(
    document.querySelectorAll("[data-step-indicator]"),
  );
  const stepPanels = Array.from(document.querySelectorAll("[data-step-panel]"));
  const nextStepButtons = Array.from(
    document.querySelectorAll("[data-step-next]"),
  );
  const prevStepButtons = Array.from(
    document.querySelectorAll("[data-step-prev]"),
  );
  const overviewEditorHost = document.getElementById("overview-editor");
  const overviewToolbar = document.getElementById("overview-toolbar");
  const overviewImageInput = document.getElementById("overview-image-input");
  const addItineraryButton = document.getElementById("add-itinerary-btn");
  const itineraryList = document.getElementById("itinerary-list");
  const addFaqButton = document.getElementById("add-faq-btn");
  const faqList = document.getElementById("faq-list");
  let overviewQuill = null;
  let currentStep = 1;
  const totalSteps = 3;

  if (!(form instanceof HTMLFormElement)) return;

  const fields = [
    "id",
    "title",
    "category",
    "region",
    "state",
    "trekType",
    "duration",
    "altitude",
    "difficulty",
    "price",
    "groupSize",
    "bestSeason",
    "image",
    "description",
    "slug",
    "canonicalUrl",
    "metaTitle",
    "metaDescription",
    "overview",
    "overviewPdfName",
    "itinerary",
    "faq",
    "inclusions",
    "exclusions",
    "keywords",
    "ogTitle",
    "ogDescription",
    "ogImage",
  ];

  function setCurrentStep(stepNumber) {
    currentStep = Math.min(Math.max(stepNumber, 1), totalSteps);

    stepPanels.forEach((panel) => {
      if (!(panel instanceof HTMLElement)) return;
      const panelStep = Number(panel.dataset.stepPanel);
      const isActive = panelStep === currentStep;
      panel.classList.toggle("active", isActive);
      panel.hidden = !isActive;
    });

    stepIndicators.forEach((indicator) => {
      if (!(indicator instanceof HTMLButtonElement)) return;
      const indicatorStep = Number(indicator.dataset.stepIndicator);
      indicator.classList.toggle("active", indicatorStep === currentStep);
      indicator.classList.toggle("completed", indicatorStep < currentStep);
      indicator.setAttribute(
        "aria-selected",
        indicatorStep === currentStep ? "true" : "false",
      );
    });
  }

  function validateStep(stepNumber) {
    const activePanel = stepPanels.find(
      (panel) =>
        panel instanceof HTMLElement &&
        Number(panel.dataset.stepPanel) === stepNumber,
    );

    if (!(activePanel instanceof HTMLElement)) return true;

    const validationTargets = Array.from(
      activePanel.querySelectorAll("input, select, textarea"),
    ).filter((field) => {
      if (
        !(field instanceof HTMLInputElement) &&
        !(field instanceof HTMLSelectElement) &&
        !(field instanceof HTMLTextAreaElement)
      ) {
        return false;
      }

      if (field.disabled || field.type === "hidden") return false;
      return true;
    });

    for (const field of validationTargets) {
      if (!field.checkValidity()) {
        field.reportValidity();
        return false;
      }
    }

    return true;
  }

  function resetStepFlow() {
    setCurrentStep(1);
  }

  function renderTreks() {
    if (!(list instanceof HTMLElement)) return;

    if (!state.treks.length) {
      list.innerHTML = '<p class="empty">No trek packages added yet.</p>';
      return;
    }

    list.innerHTML = state.treks
      .map(
        (trek) => `
      <article class="item-card">
        <img src="${trek.image}" alt="${trek.title}" />
        <div class="item-card-content">
          <h4>${trek.title}</h4>
          <p class="meta">${trek.category || "Domestic"} | Type: ${trek.trekType || "N/A"}</p>
          <p class="meta">State: ${trek.region || "N/A"}</p>
          <p class="meta">${trek.duration} | ${trek.difficulty}</p>
          <p class="meta">Altitude ${trek.altitude}m | INR ${Number(trek.price || 0).toLocaleString("en-IN")}</p>
          <p>${trek.description}</p>
          <p class="meta">SEO Slug: ${trek.slug}</p>
          <p class="meta">Meta: ${trek.metaTitle}</p>
          <p class="meta">Overview PDF: ${trek.overviewPdfName || "Not uploaded"}</p>
          <p class="meta">Itinerary Days: ${Array.isArray(trek.itinerary) ? trek.itinerary.length : 0}</p>
          <p class="meta">FAQs: ${Array.isArray(trek.faq) ? trek.faq.length : 0}</p>
          <div class="overview-preview">${trek.overview || escapeHtml(trek.description || "")}</div>
          <p class="meta"><strong>Inclusions:</strong> ${escapeHtml(trek.inclusions || "NA")}</p>
          <p class="meta"><strong>Exclusions:</strong> ${escapeHtml(trek.exclusions || "NA")}</p>
          <div class="itinerary-preview">
            ${(Array.isArray(trek.itinerary) ? trek.itinerary : [])
              .slice(0, 3)
              .map(
                (item) => `
              <div class="itinerary-preview-item">
                <strong>${escapeHtml(item.day || "Day")}</strong>
                <span>${escapeHtml(item.title || "")}</span>
              </div>
            `,
              )
              .join("")}
          </div>
          <div class="faq-preview">
            ${(Array.isArray(trek.faq) ? trek.faq : [])
              .slice(0, 2)
              .map(
                (item) => `
              <div class="faq-preview-item">
                <strong>Q: ${escapeHtml(item.question || "")}</strong>
                <span>A: ${escapeHtml(item.answer || "")}</span>
              </div>
            `,
              )
              .join("")}
          </div>
          <div class="card-actions">
            <button class="edit-btn" data-kind="trek" data-id="${trek.id}">Edit</button>
            <button class="delete-btn" data-kind="trek" data-id="${trek.id}">Delete</button>
          </div>
        </div>
      </article>
    `,
      )
      .join("");
  }

  function syncOverviewToHiddenInput() {
    const hiddenOverviewInput = form.elements.namedItem("overview");
    if (!(hiddenOverviewInput instanceof HTMLInputElement) || !overviewQuill)
      return;
    hiddenOverviewInput.value = overviewQuill.root.innerHTML.trim();
  }

  function setOverviewContent(html) {
    if (!overviewQuill) return;

    if (html) {
      overviewQuill.clipboard.dangerouslyPasteHTML(html);
    } else {
      overviewQuill.setText("");
    }

    syncOverviewToHiddenInput();
  }

  function parseItinerary(value) {
    if (!value) return [];
    if (Array.isArray(value)) return value;

    try {
      const parsed = JSON.parse(value);
      return Array.isArray(parsed) ? parsed : [];
    } catch {
      return [];
    }
  }

  function createItineraryRow(item = {}) {
    const row = document.createElement("div");
    row.className = "itinerary-row";
    row.innerHTML = `
      <div class="itinerary-row-grid">
        <label>
          Day
          <input type="text" name="itineraryDay" placeholder="Day 1" value="${escapeHtml(item.day || "")}" />
        </label>
        <label>
          Title
          <input type="text" name="itineraryTitle" placeholder="Arrival and Briefing" value="${escapeHtml(item.title || "")}" />
        </label>
        <label class="full">
          Details
          <textarea name="itineraryDetails" rows="3" placeholder="Describe the activities, stay, and travel...">${escapeHtml(item.details || "")}</textarea>
        </label>
      </div>
      <button type="button" class="toolbar-btn itinerary-remove-btn">Remove</button>
    `;

    return row;
  }

  function readItineraryFromDom() {
    if (!(itineraryList instanceof HTMLElement)) return [];

    return Array.from(itineraryList.querySelectorAll(".itinerary-row"))
      .map((row) => {
        const dayInput = row.querySelector('[name="itineraryDay"]');
        const titleInput = row.querySelector('[name="itineraryTitle"]');
        const detailsInput = row.querySelector('[name="itineraryDetails"]');

        return {
          day:
            dayInput instanceof HTMLInputElement ? dayInput.value.trim() : "",
          title:
            titleInput instanceof HTMLInputElement
              ? titleInput.value.trim()
              : "",
          details:
            detailsInput instanceof HTMLTextAreaElement
              ? detailsInput.value.trim()
              : "",
        };
      })
      .filter((item) => item.day || item.title || item.details);
  }

  function syncItineraryToHiddenInput() {
    const hiddenItineraryInput = form.elements.namedItem("itinerary");
    if (!(hiddenItineraryInput instanceof HTMLInputElement)) return;
    hiddenItineraryInput.value = JSON.stringify(readItineraryFromDom());
  }

  function renderItineraryRows(items = []) {
    if (!(itineraryList instanceof HTMLElement)) return;

    itineraryList.innerHTML = "";
    const entries = items.length
      ? items
      : [{ day: "Day 1", title: "", details: "" }];
    entries.forEach((item) =>
      itineraryList.appendChild(createItineraryRow(item)),
    );
    syncItineraryToHiddenInput();
  }

  function parseFaq(value) {
    if (!value) return [];
    if (Array.isArray(value)) return value;

    try {
      const parsed = JSON.parse(value);
      return Array.isArray(parsed) ? parsed : [];
    } catch {
      return [];
    }
  }

  function startEditingRecord(record) {
    fillForm(form, record, fields);
    setOverviewContent(record.overview || "");
    renderItineraryRows(parseItinerary(record.itinerary));
    renderFaqRows(parseFaq(record.faq));

    const pdfInput = form.elements.namedItem("overviewPdf");
    if (pdfInput instanceof HTMLInputElement) {
      pdfInput.value = "";
    }

    if (formTitle) formTitle.textContent = "Edit Trek Package";
    if (submitButton) submitButton.textContent = "Update Trek";
    resetStepFlow();
    form.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  function createFaqRow(item = {}) {
    const row = document.createElement("div");
    row.className = "itinerary-row faq-row";
    row.innerHTML = `
      <div class="itinerary-row-grid">
        <label class="full">
          Question
          <input type="text" name="faqQuestion" placeholder="Is this trek beginner friendly?" value="${escapeHtml(item.question || "")}" />
        </label>
        <label class="full">
          Answer
          <textarea name="faqAnswer" rows="3" placeholder="Yes, this route is suitable for fit beginners.">${escapeHtml(item.answer || "")}</textarea>
        </label>
      </div>
      <button type="button" class="toolbar-btn faq-remove-btn">Remove</button>
    `;

    return row;
  }

  function readFaqFromDom() {
    if (!(faqList instanceof HTMLElement)) return [];

    return Array.from(faqList.querySelectorAll(".faq-row"))
      .map((row) => {
        const questionInput = row.querySelector('[name="faqQuestion"]');
        const answerInput = row.querySelector('[name="faqAnswer"]');

        return {
          question:
            questionInput instanceof HTMLInputElement
              ? questionInput.value.trim()
              : "",
          answer:
            answerInput instanceof HTMLTextAreaElement
              ? answerInput.value.trim()
              : "",
        };
      })
      .filter((item) => item.question || item.answer);
  }

  function syncFaqToHiddenInput() {
    const hiddenFaqInput = form.elements.namedItem("faq");
    if (!(hiddenFaqInput instanceof HTMLInputElement)) return;
    hiddenFaqInput.value = JSON.stringify(readFaqFromDom());
  }

  function renderFaqRows(items = []) {
    if (!(faqList instanceof HTMLElement)) return;

    faqList.innerHTML = "";
    const entries = items.length ? items : [{ question: "", answer: "" }];
    entries.forEach((item) => faqList.appendChild(createFaqRow(item)));
    syncFaqToHiddenInput();
  }

  if (
    typeof Quill === "function" &&
    overviewEditorHost instanceof HTMLElement &&
    overviewToolbar instanceof HTMLElement
  ) {
    const imageHandler = () => {
      if (overviewImageInput instanceof HTMLInputElement) {
        overviewImageInput.click();
      }
    };

    overviewQuill = new Quill(overviewEditorHost, {
      theme: "snow",
      placeholder:
        "Write a detailed trek overview here, like a Word document. You can also insert images.",
      modules: {
        toolbar: {
          container: overviewToolbar,
          handlers: {
            image: imageHandler,
          },
        },
      },
    });

    overviewQuill.on("text-change", syncOverviewToHiddenInput);

    const hiddenOverviewInput = form.elements.namedItem("overview");
    if (hiddenOverviewInput instanceof HTMLInputElement) {
      hiddenOverviewInput.value = overviewQuill.root.innerHTML.trim();
    }
  }

  if (overviewImageInput instanceof HTMLInputElement) {
    overviewImageInput.addEventListener("change", async () => {
      const file = overviewImageInput.files && overviewImageInput.files[0];
      if (!file) return;

      const dataUrl = await readFileAsDataUrl(file);
      if (overviewQuill) {
        const range = overviewQuill.getSelection(true) || {
          index: overviewQuill.getLength(),
          length: 0,
        };
        overviewQuill.insertEmbed(range.index, "image", dataUrl, "user");
        overviewQuill.setSelection(range.index + 1, 0, "user");
        syncOverviewToHiddenInput();
      }
      overviewImageInput.value = "";
    });
  }

  if (
    addItineraryButton instanceof HTMLButtonElement &&
    itineraryList instanceof HTMLElement
  ) {
    addItineraryButton.addEventListener("click", () => {
      const nextDayNumber =
        itineraryList.querySelectorAll(".itinerary-row").length + 1;
      itineraryList.appendChild(
        createItineraryRow({ day: `Day ${nextDayNumber}` }),
      );
      syncItineraryToHiddenInput();
    });

    itineraryList.addEventListener("input", syncItineraryToHiddenInput);
    itineraryList.addEventListener("click", (event) => {
      const target = event.target;
      if (!(target instanceof HTMLButtonElement)) return;
      if (!target.classList.contains("itinerary-remove-btn")) return;

      const row = target.closest(".itinerary-row");
      if (row) row.remove();

      if (!itineraryList.querySelector(".itinerary-row")) {
        itineraryList.appendChild(createItineraryRow({ day: "Day 1" }));
      }

      syncItineraryToHiddenInput();
    });
  }

  if (
    addFaqButton instanceof HTMLButtonElement &&
    faqList instanceof HTMLElement
  ) {
    addFaqButton.addEventListener("click", () => {
      faqList.appendChild(createFaqRow());
      syncFaqToHiddenInput();
    });

    faqList.addEventListener("input", syncFaqToHiddenInput);
    faqList.addEventListener("click", (event) => {
      const target = event.target;
      if (!(target instanceof HTMLButtonElement)) return;
      if (!target.classList.contains("faq-remove-btn")) return;

      const row = target.closest(".faq-row");
      if (row) row.remove();

      if (!faqList.querySelector(".faq-row")) {
        faqList.appendChild(createFaqRow());
      }

      syncFaqToHiddenInput();
    });
  }

  nextStepButtons.forEach((button) => {
    if (!(button instanceof HTMLButtonElement)) return;

    button.addEventListener("click", () => {
      if (!validateStep(currentStep)) return;
      syncOverviewToHiddenInput();
      syncItineraryToHiddenInput();
      syncFaqToHiddenInput();
      setCurrentStep(currentStep + 1);
    });
  });

  prevStepButtons.forEach((button) => {
    if (!(button instanceof HTMLButtonElement)) return;

    button.addEventListener("click", () => {
      setCurrentStep(currentStep - 1);
    });
  });

  stepIndicators.forEach((indicator) => {
    if (!(indicator instanceof HTMLButtonElement)) return;

    indicator.addEventListener("click", () => {
      const targetStep = Number(indicator.dataset.stepIndicator);
      if (!targetStep || targetStep === currentStep) return;

      if (targetStep > currentStep && !validateStep(currentStep)) return;
      setCurrentStep(targetStep);
    });
  });

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    if (currentStep < totalSteps) {
      if (!validateStep(currentStep)) return;
      setCurrentStep(currentStep + 1);
      return;
    }

    if (!validateStep(currentStep)) return;

    syncOverviewToHiddenInput();
    syncItineraryToHiddenInput();
    syncFaqToHiddenInput();
    const payload = readFormData(form, fields);
    const now = Date.now();
    const pdfInput = form.elements.namedItem("overviewPdf");
    const existingTrek = payload.id
      ? state.treks.find((trek) => trek.id === payload.id)
      : null;

    if (
      pdfInput instanceof HTMLInputElement &&
      pdfInput.files &&
      pdfInput.files[0]
    ) {
      const file = pdfInput.files[0];
      payload.overviewPdfName = file.name;
      payload.overviewPdfData = await readFileAsDataUrl(file);
    } else if (existingTrek) {
      payload.overviewPdfName = existingTrek.overviewPdfName || "";
      payload.overviewPdfData = existingTrek.overviewPdfData || "";
    }

    if (!payload.id) {
      payload.id = crypto.randomUUID();
      payload.createdAt = now;
      state.treks.unshift(payload);
    } else {
      state.treks = state.treks.map((trek) =>
        trek.id === payload.id ? { ...trek, ...payload } : trek,
      );
    }

    saveItems();
    renderTreks();
    clearForm(
      form,
      formTitle,
      "Update Trek Package",
      submitButton,
      "Save Trek",
    );
    setOverviewContent("");
    renderItineraryRows();
    renderFaqRows();
    resetStepFlow();
  });

  if (list instanceof HTMLElement) {
    list.addEventListener("click", (event) => {
      const target = event.target;
      if (!(target instanceof HTMLButtonElement)) return;

      const id = target.dataset.id;
      const kind = target.dataset.kind;
      if (!id || kind !== "trek") return;

      if (target.classList.contains("delete-btn")) {
        state.treks = state.treks.filter((trek) => trek.id !== id);
        saveItems();
        renderTreks();
        return;
      }

      if (target.classList.contains("edit-btn")) {
        const record = state.treks.find((trek) => trek.id === id);
        if (!record) return;
        startEditingRecord(record);
      }
    });
  }

  if (cancelButton instanceof HTMLButtonElement) {
    cancelButton.addEventListener("click", () => {
      clearForm(
        form,
        formTitle,
        "Update Trek Package",
        submitButton,
        "Save Trek",
      );
      setOverviewContent("");
      renderItineraryRows();
      renderFaqRows();
      resetStepFlow();
      const pdfInput = form.elements.namedItem("overviewPdf");
      if (pdfInput instanceof HTMLInputElement) {
        pdfInput.value = "";
      }
    });
  }

  renderTreks();
  renderItineraryRows();
  renderFaqRows();
  resetStepFlow();

  const editIdFromQuery = new URLSearchParams(window.location.search).get(
    "edit",
  );
  if (editIdFromQuery) {
    const record = state.treks.find((trek) => trek.id === editIdFromQuery);
    if (record) {
      startEditingRecord(record);
    }
  }
}

function setupBlogsPage() {
  const form = document.getElementById("blog-form");
  const list = document.getElementById("blog-list");
  const formTitle = document.getElementById("blog-form-title");
  const submitButton = document.getElementById("blog-submit-btn");
  const cancelButton = document.getElementById("blog-cancel-btn");

  if (!(form instanceof HTMLFormElement) || !(list instanceof HTMLElement))
    return;

  const fields = [
    "id",
    "title",
    "category",
    "readTime",
    "date",
    "image",
    "excerpt",
    "slug",
    "canonicalUrl",
    "metaTitle",
    "metaDescription",
    "keywords",
  ];

  function renderBlogs() {
    if (!state.blogs.length) {
      list.innerHTML = '<p class="empty">No blogs added yet.</p>';
      return;
    }

    list.innerHTML = state.blogs
      .map(
        (blog) => `
      <article class="item-card">
        <img src="${blog.image}" alt="${blog.title}" />
        <div class="item-card-content">
          <h4>${blog.title}</h4>
          <p class="meta">${blog.category} | ${blog.readTime} | ${blog.date}</p>
          <p>${blog.excerpt}</p>
          <p class="meta">SEO Slug: ${blog.slug}</p>
          <p class="meta">Meta: ${blog.metaTitle}</p>
          <div class="card-actions">
            <button class="edit-btn" data-kind="blog" data-id="${blog.id}">Edit</button>
            <button class="delete-btn" data-kind="blog" data-id="${blog.id}">Delete</button>
          </div>
        </div>
      </article>
    `,
      )
      .join("");
  }

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    const payload = readFormData(form, fields);
    const now = Date.now();

    if (!payload.id) {
      payload.id = crypto.randomUUID();
      payload.createdAt = now;
      state.blogs.unshift(payload);
    } else {
      state.blogs = state.blogs.map((blog) =>
        blog.id === payload.id ? { ...blog, ...payload } : blog,
      );
    }

    saveItems();
    renderBlogs();
    clearForm(form, formTitle, "Add Blog", submitButton, "Save Blog");
  });

  list.addEventListener("click", (event) => {
    const target = event.target;
    if (!(target instanceof HTMLButtonElement)) return;

    const id = target.dataset.id;
    const kind = target.dataset.kind;
    if (!id || kind !== "blog") return;

    if (target.classList.contains("delete-btn")) {
      state.blogs = state.blogs.filter((blog) => blog.id !== id);
      saveItems();
      renderBlogs();
      return;
    }

    if (target.classList.contains("edit-btn")) {
      const record = state.blogs.find((blog) => blog.id === id);
      if (!record) return;
      fillForm(form, record, fields);
      if (formTitle) formTitle.textContent = "Edit Blog";
      if (submitButton) submitButton.textContent = "Update Blog";
      form.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  });

  if (cancelButton instanceof HTMLButtonElement) {
    cancelButton.addEventListener("click", () => {
      clearForm(form, formTitle, "Add Blog", submitButton, "Save Blog");
    });
  }

  renderBlogs();
}

function setupQuotationsPage() {
  const form = document.getElementById("quote-form");
  const list = document.getElementById("quote-list");
  const formTitle = document.getElementById("quote-form-title");
  const submitButton = document.getElementById("quote-submit-btn");
  const cancelButton = document.getElementById("quote-cancel-btn");

  if (!(list instanceof HTMLElement)) return;

  const fields = [
    "id",
    "customerName",
    "phone",
    "email",
    "destination",
    "travelDate",
    "travelers",
    "budget",
    "status",
    "message",
  ];

  function renderQuotations() {
    if (!state.quotations.length) {
      list.innerHTML = '<p class="empty">No quotation submissions yet.</p>';
      return;
    }

    list.innerHTML = state.quotations
      .map(
        (quote) => `
      <article class="item-card">
        <div class="item-card-content">
          <h4>${quote.customerName}</h4>
          <p class="meta">${quote.phone} | ${quote.email}</p>
          <p class="meta">${quote.destination} | ${quote.travelDate}</p>
          <p class="meta">Travelers: ${quote.travelers} | Budget: INR ${Number(quote.budget || 0).toLocaleString("en-IN")}</p>
          <p class="meta">Status: ${quote.status}</p>
          <p>${quote.message}</p>
          <div class="card-actions">
            <button class="edit-btn" data-kind="quote" data-id="${quote.id}">Edit Status</button>
            <button class="delete-btn" data-kind="quote" data-id="${quote.id}">Delete</button>
          </div>
        </div>
      </article>
    `,
      )
      .join("");
  }

  if (form instanceof HTMLFormElement) {
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      const payload = readFormData(form, fields);
      const now = Date.now();

      if (!payload.id) {
        payload.id = crypto.randomUUID();
        payload.createdAt = now;
        state.quotations.unshift(payload);
      } else {
        state.quotations = state.quotations.map((quote) =>
          quote.id === payload.id ? { ...quote, ...payload } : quote,
        );
      }

      saveItems();
      renderQuotations();
      clearForm(
        form,
        formTitle,
        "Add Quotation Submission",
        submitButton,
        "Save Submission",
      );
    });
  }

  list.addEventListener("click", (event) => {
    const target = event.target;
    if (!(target instanceof HTMLButtonElement)) return;

    const id = target.dataset.id;
    const kind = target.dataset.kind;
    if (!id || kind !== "quote") return;

    if (target.classList.contains("delete-btn")) {
      state.quotations = state.quotations.filter((quote) => quote.id !== id);
      saveItems();
      renderQuotations();
      return;
    }

    if (target.classList.contains("edit-btn")) {
      const record = state.quotations.find((quote) => quote.id === id);
      if (!record) return;

      const optionsText = QUOTATION_STATUSES.join(" / ");
      const nextStatus = window.prompt(
        `Update status for ${record.customerName}.\nAvailable: ${optionsText}`,
        record.status || "New Lead",
      );

      if (nextStatus === null) return;

      const normalizedStatus = nextStatus.trim();
      if (!QUOTATION_STATUSES.includes(normalizedStatus)) {
        window.alert(`Invalid status. Please use one of: ${optionsText}`);
        return;
      }

      state.quotations = state.quotations.map((quote) =>
        quote.id === id ? { ...quote, status: normalizedStatus } : quote,
      );
      saveItems();
      renderQuotations();
    }
  });

  if (
    cancelButton instanceof HTMLButtonElement &&
    form instanceof HTMLFormElement
  ) {
    cancelButton.addEventListener("click", () => {
      clearForm(
        form,
        formTitle,
        "Add Quotation Submission",
        submitButton,
        "Save Submission",
      );
    });
  }

  renderQuotations();
}

function setupTrekManagementPage() {
  const list = document.getElementById("trek-management-list");
  if (!(list instanceof HTMLElement)) return;

  if (!state.treks.length) {
    list.innerHTML = '<p class="empty">No trek packages added yet.</p>';
    return;
  }

  list.innerHTML = state.treks
    .map(
      (trek) => `
    <article class="item-card">
      <img src="${trek.image}" alt="${trek.title}" />
      <div class="item-card-content">
        <h4>${trek.title}</h4>
        <p class="meta">${trek.category || "Domestic"} | State: ${trek.region || "N/A"}</p>
        <p class="meta">${trek.duration} | ${trek.difficulty}</p>
        <p class="meta">Altitude ${trek.altitude}m | INR ${Number(trek.price || 0).toLocaleString("en-IN")}</p>
        <p>${trek.description}</p>
        <p class="meta">SEO Slug: ${trek.slug}</p>
        <div class="card-actions">
          <a class="edit-btn" href="treks.php?edit=${encodeURIComponent(trek.id)}">Edit</a>
        </div>
      </div>
    </article>
  `,
    )
    .join("");
}

function setupRentalLeadsPage() {
  const list = document.getElementById("rental-leads-list");
  if (!(list instanceof HTMLElement)) return;

  if (!state.rentalLeads.length) {
    list.innerHTML = '<p class="empty">No car rental leads yet.</p>';
    return;
  }

  list.innerHTML = state.rentalLeads
    .map(
      (lead) => `
    <article class="item-card">
      <div class="item-card-content">
        <h4>${lead.customerName}</h4>
        <p class="meta">${lead.phone} | ${lead.email || "NA"}</p>
        <p class="meta">Car: ${lead.carType || "NA"} | Route: ${lead.route || "NA"}</p>
        <p class="meta">Pickup: ${lead.pickupDate || "NA"} | Travelers: ${lead.travelers || "NA"}</p>
        <p class="meta">Budget: INR ${Number(lead.budget || 0).toLocaleString("en-IN")} | Status: ${lead.status || "New Lead"}</p>
        <p>${lead.message || "No additional note."}</p>
      </div>
    </article>
  `,
    )
    .join("");
}

function setupHotelLeadsPage() {
  const list = document.getElementById("hotel-leads-list");
  if (!(list instanceof HTMLElement)) return;

  if (!state.hotelLeads.length) {
    list.innerHTML = '<p class="empty">No hotel leads yet.</p>';
    return;
  }

  list.innerHTML = state.hotelLeads
    .map(
      (lead) => `
    <article class="item-card">
      <div class="item-card-content">
        <h4>${lead.customerName}</h4>
        <p class="meta">${lead.phone} | ${lead.email || "NA"}</p>
        <p class="meta">City: ${lead.city || "NA"} | Rooms: ${lead.rooms || "NA"} | Guests: ${lead.guests || "NA"}</p>
        <p class="meta">Stay: ${lead.checkInDate || "NA"} to ${lead.checkOutDate || "NA"}</p>
        <p class="meta">Budget: INR ${Number(lead.budget || 0).toLocaleString("en-IN")} | Status: ${lead.status || "New Lead"}</p>
        <p>${lead.message || "No additional note."}</p>
      </div>
    </article>
  `,
    )
    .join("");
}

function init() {
  const page = document.body.dataset.page;
  if (!enforceAuth(page)) return;

  setupLogout();
  setupResponsiveSidebar();
  seedDemoTreks();
  seedDemoQuotations();
  seedDemoRentalLeads();
  seedDemoHotelLeads();

  if (page === "dashboard") {
    renderDashboard();
  }

  if (page === "treks") {
    setupTreksPage();
  }

  if (page === "trek-management") {
    setupTrekManagementPage();
  }

  if (page === "blogs") {
    setupBlogsPage();
  }

  if (page === "quotations") {
    setupQuotationsPage();
  }

  if (page === "rental-leads") {
    setupRentalLeadsPage();
  }

  if (page === "hotel-leads") {
    setupHotelLeadsPage();
  }
}

init();
