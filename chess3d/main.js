var scene, camera, renderer, controls;
var pieces; // Group to hold chess pieces
var validMoveIndicators = []; // Array to hold valid move indicators

var raycaster = new THREE.Raycaster();
var mouse = new THREE.Vector2();
var selectedObject = null;
var dragPlane = new THREE.Plane();
var offset = new THREE.Vector3();

// Game state
var currentTurn = 'white'; // 'white' or 'black'
var board = Array(8).fill().map(() => Array(8).fill(null)); // 8x8 board representation
var isCheck = false;       // Flag to track check state
var isCheckmate = false;   // Flag to track checkmate state
var lastMove = null;       // Track the last move made
var pieceHasMoved = {};    // Track if a piece has moved (for castling)
var enPassantTarget = null; // Track en passant possibility
var promotionInProgress = null; // Track promotion in progress
var moveHistory = [];      // Array to store move history
var capturedPieces = {     // Track captured pieces
    'white': [], // Pieces captured by white player (black pieces)
    'black': []  // Pieces captured by black player (white pieces)
};

// Set a flag to check initialization
var initialized = false;
var modelsLoaded = false;
var pieceModels = {};

// Game settings with default values
var gameSettings = {
    boardLightColor: 0xF5F5DC, // cream color for light squares
    boardDarkColor: 0x8B4513,  // dark brown for dark squares
    boardBorderColor: 0x5D4037, // brown border
    whitePieceColor: 0xF0F0F0, // slightly off-white
    blackPieceColor: 0x111111, // very dark gray
    cameraDistance: 12,        // distance from camera to board
    cameraAngle: 45,           // camera angle in degrees
    showCoordinates: true      // show board coordinates
};

// Chess piece model URLs - using online models for simplicity
const modelURLs = {
    'white': {
        'pawn': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/pawn.glb',
        'rook': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/rook.glb',
        'knight': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/knight.glb',
        'bishop': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/bishop.glb',
        'queen': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/queen.glb',
        'king': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/king.glb'
    },
    'black': {
        'pawn': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/pawn.glb',
        'rook': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/rook.glb',
        'knight': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/knight.glb',
        'bishop': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/bishop.glb',
        'queen': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/queen.glb',
        'king': 'https://raw.githubusercontent.com/baronwatts/models/master/chess/king.glb'
    }
};

// Scale factors for each piece type
const modelScales = {
    'pawn': 0.8,
    'rook': 0.8,
    'knight': 0.8,
    'bishop': 0.8,
    'queen': 0.8,
    'king': 0.8
};

// Required model types and colors
const requiredModels = [
    { type: 'pawn', color: 'white' },
    { type: 'rook', color: 'white' },
    { type: 'knight', color: 'white' },
    { type: 'bishop', color: 'white' },
    { type: 'queen', color: 'white' },
    { type: 'king', color: 'white' },
    { type: 'pawn', color: 'black' },
    { type: 'rook', color: 'black' },
    { type: 'knight', color: 'black' },
    { type: 'bishop', color: 'black' },
    { type: 'queen', color: 'black' },
    { type: 'king', color: 'black' }
];

// Sound system
var sounds = {
    move: null,
    capture: null,
    check: null,
    checkmate: null,
    promote: null,
    castle: null
};

// Load all sound effects
function loadSounds() {
    const audioLoader = new THREE.AudioLoader();
    const listener = new THREE.AudioListener();
    camera.add(listener);
    
    // Create audio objects
    sounds.move = new THREE.Audio(listener);
    sounds.capture = new THREE.Audio(listener);
    sounds.check = new THREE.Audio(listener);
    sounds.checkmate = new THREE.Audio(listener);
    sounds.promote = new THREE.Audio(listener);
    sounds.castle = new THREE.Audio(listener);
    
    // Load sound files
    audioLoader.load('sounds/move.mp3', buffer => sounds.move.setBuffer(buffer));
    audioLoader.load('sounds/capture.mp3', buffer => sounds.capture.setBuffer(buffer));
    audioLoader.load('sounds/check.mp3', buffer => sounds.check.setBuffer(buffer));
    audioLoader.load('sounds/checkmate.mp3', buffer => sounds.checkmate.setBuffer(buffer));
    audioLoader.load('sounds/promote.mp3', buffer => sounds.promote.setBuffer(buffer));
    audioLoader.load('sounds/castle.mp3', buffer => sounds.castle.setBuffer(buffer));
}

// Play a sound effect
function playSound(soundType) {
    if (sounds[soundType] && sounds[soundType].buffer) {
        if (sounds[soundType].isPlaying) {
            sounds[soundType].stop();
        }
        sounds[soundType].play();
    }
}

function init() {
    try {
        // Create game UI components
        createGameUI();
        
        // Update info display
        var infoElement = document.getElementById('info');
        if (infoElement) {
            infoElement.innerHTML = '3D Satranç Oyunu - Başlatılıyor...';
        }
        
        // Create scene with a background color
        scene = new THREE.Scene();
        scene.background = new THREE.Color(0x303030);
        
        // Create camera with proper position
        camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
        // Position camera based on settings
        const angleRad = (gameSettings.cameraAngle * Math.PI) / 180;
        const distance = gameSettings.cameraDistance;
        camera.position.y = distance * Math.sin(angleRad);
        const horizontalDistance = distance * Math.cos(angleRad);
        camera.position.z = horizontalDistance;
        camera.position.x = 0;
        camera.lookAt(0, 0, 0);

        // Create renderer with proper settings
        renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        
        // Enable shadows
        renderer.shadowMap.enabled = true;
        renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        
        document.body.appendChild(renderer.domElement);

        // Debug message
        console.log('Renderer created:', renderer);

        // OrbitControls setup with better defaults
        controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.25;
        controls.screenSpacePanning = false;
        controls.minDistance = 5;
        controls.maxDistance = 20;
        controls.maxPolarAngle = Math.PI / 2.5; // Limit angle to prevent viewing from below
        controls.minPolarAngle = Math.PI / 6;   // Minimum angle to prevent top-down view
        controls.target.set(0, 0, 0);           // Set target to board center
        
        // Ambient light
        var ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);

        // Directional light with shadows
        var directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(5, 10, 7.5);
        directionalLight.castShadow = true;
        
        // Optimize shadow settings
        directionalLight.shadow.mapSize.width = 1024;
        directionalLight.shadow.mapSize.height = 1024;
        directionalLight.shadow.camera.near = 0.5;
        directionalLight.shadow.camera.far = 50;
        directionalLight.shadow.camera.left = -10;
        directionalLight.shadow.camera.right = 10;
        directionalLight.shadow.camera.top = 10;
        directionalLight.shadow.camera.bottom = -10;
        
        scene.add(directionalLight);
        
        // Add a secondary light for better illumination
        var secondaryLight = new THREE.DirectionalLight(0xffffff, 0.4);
        secondaryLight.position.set(-5, 8, -7.5);
        scene.add(secondaryLight);
        
        // Create chess board (8x8 grid)
        var chessBoard = createChessBoard();
        scene.add(chessBoard);
        
        // Debug message
        console.log('Board created:', chessBoard);
        
        // Load 3D models for chess pieces
        loadAllModels().then(() => {
            // Create chess pieces once models are loaded
            pieces = createAllPieces(chessBoard.position);
            scene.add(pieces);
            
            // Initialize board state
            updateBoardState();
            
            // Debug message
            console.log('Pieces created:', pieces);
            
            // Add mousedown listener for selecting pieces
            renderer.domElement.addEventListener('mousedown', onMouseDown, false);
            
            // Update initialization flag
            modelsLoaded = true;
            
            // Hide loading message
            document.getElementById('loading').style.display = 'none';
            
            // Update info display
            if (infoElement) {
                infoElement.innerHTML = '3D Satranç Oyunu - Hazır (Sıra: ' + (currentTurn === 'white' ? 'beyaz' : 'siyah') + ')';
            }
        }).catch(error => {
            console.error('Error loading models:', error);
            document.getElementById('info').innerHTML = 'Model yükleme hatası: ' + error.message;
            document.getElementById('loading').innerHTML = 'Satranç taşları yüklenemedi. Lütfen tekrar deneyin.';
        });

        window.addEventListener('resize', onWindowResize, false);
        
        // Update initialization flag
        initialized = true;
        
        // Start animation loop
        animate();
        
        // Load sound effects
        loadSounds();
        
    } catch (error) {
        console.error('Initialization error:', error);
        document.getElementById('info').innerHTML = 'Hata: ' + error.message;
    }
}

// Load all required models
function loadAllModels() {
    return new Promise((resolve, reject) => {
        let loadingPromises = [];
        
        // Create a loader
        const loader = new THREE.GLTFLoader();
        
        // Load each required model
        requiredModels.forEach(model => {
            const url = modelURLs[model.color][model.type];
            const loadPromise = new Promise((resolveModel, rejectModel) => {
                loader.load(
                    url,
                    (gltf) => {
                        // Store the model
                        if (!pieceModels[model.color]) {
                            pieceModels[model.color] = {};
                        }
                        
                        // Process the loaded model - clone it and set material colors
                        const loadedModel = gltf.scene.clone();
                        
                        // Apply material color based on piece color and settings
                        loadedModel.traverse(child => {
                            if (child.isMesh) {
                                // Create better materials for the pieces
                                if (model.color === 'white') {
                                    child.material = new THREE.MeshPhongMaterial({
                                        color: gameSettings.whitePieceColor,
                                        emissive: 0x222222,
                                        shininess: 100,
                                        specular: 0x666666
                                    });
                                } else {
                                    child.material = new THREE.MeshPhongMaterial({
                                        color: gameSettings.blackPieceColor,
                                        emissive: 0x111111,
                                        shininess: 100,
                                        specular: 0x666666
                                    });
                                }
                                
                                // Enable shadows
                                child.castShadow = true;
                                child.receiveShadow = true;
                            }
                        });
                        
                        // Rotate black pieces to face the opposite direction
                        if (model.color === 'black') {
                            loadedModel.rotation.y = Math.PI;
                        }
                        
                        // Apply special rotations or adjustments for specific pieces
                        if (model.type === 'knight') {
                            // Knights often need special rotation
                            loadedModel.rotation.x = 0;
                            if (model.color === 'white') {
                                loadedModel.rotation.y = Math.PI / 2;
                            } else {
                                loadedModel.rotation.y = -Math.PI / 2;
                            }
                        }
                        
                        // Center the model
                        const box = new THREE.Box3().setFromObject(loadedModel);
                        const center = box.getCenter(new THREE.Vector3());
                        loadedModel.position.sub(center);
                        
                        // Ensure model sits on the board properly
                        const height = box.max.y - box.min.y;
                        loadedModel.position.y = 0; // Start at board level
                        
                        // Apply scale
                        const scale = modelScales[model.type];
                        loadedModel.scale.set(scale, scale, scale);
                        
                        // Store the processed model
                        pieceModels[model.color][model.type] = loadedModel;
                        
                        console.log(`Loaded ${model.color} ${model.type}`);
                        resolveModel();
                    },
                    (progress) => {
                        // Update loading progress
                        const percent = Math.round(progress.loaded / progress.total * 100);
                        console.log(`Loading ${model.color} ${model.type}: ${percent}%`);
                        
                        // Update loading message
                        document.getElementById('loading').innerHTML = 
                            `Loading Chess Pieces...<br>${model.color} ${model.type}: ${percent}%`;
                    },
                    (error) => {
                        console.error(`Error loading ${model.color} ${model.type}:`, error);
                        // If we can't load the model, create a simple geometry as fallback
                        if (!pieceModels[model.color]) {
                            pieceModels[model.color] = {};
                        }
                        
                        // Create a fallback simple piece with color from settings
                        const pieceColor = model.color === 'white' ? gameSettings.whitePieceColor : gameSettings.blackPieceColor;
                        const fallbackPiece = createSimplePiece(model.type, pieceColor);
                        pieceModels[model.color][model.type] = fallbackPiece;
                        resolveModel(); // Still resolve to continue the game
                    }
                );
            });
            
            loadingPromises.push(loadPromise);
        });
        
        // Wait for all models to load
        Promise.all(loadingPromises)
            .then(() => {
                console.log('All models loaded successfully');
                resolve();
            })
            .catch(error => {
                console.error('Error loading all models:', error);
                reject(error);
            });
    });
}

// Create fallback simple pieces if models fail to load
function createSimplePiece(type, color) {
    let geometry;
    let height = 1;
    let group = new THREE.Group();
    
    switch(type) {
        case 'pawn':
            // Pawn: cylinder base with sphere top
            const pawnBase = new THREE.CylinderGeometry(0.25, 0.3, 0.2, 16);
            const pawnBaseObj = new THREE.Mesh(pawnBase, new THREE.MeshPhongMaterial({ color: color }));
            pawnBaseObj.position.y = 0.1;
            
            const pawnMid = new THREE.CylinderGeometry(0.15, 0.25, 0.4, 16);
            const pawnMidObj = new THREE.Mesh(pawnMid, new THREE.MeshPhongMaterial({ color: color }));
            pawnMidObj.position.y = 0.4;
            
            const pawnTop = new THREE.SphereGeometry(0.2, 16, 16);
            const pawnTopObj = new THREE.Mesh(pawnTop, new THREE.MeshPhongMaterial({ color: color }));
            pawnTopObj.position.y = 0.7;
            
            group.add(pawnBaseObj);
            group.add(pawnMidObj);
            group.add(pawnTopObj);
            break;
            
        case 'rook':
            // Rook: cubic with battlements
            const rookBase = new THREE.CylinderGeometry(0.3, 0.35, 0.3, 16);
            const rookBaseObj = new THREE.Mesh(rookBase, new THREE.MeshPhongMaterial({ color: color }));
            rookBaseObj.position.y = 0.15;
            
            const rookBody = new THREE.CylinderGeometry(0.25, 0.3, 0.6, 16);
            const rookBodyObj = new THREE.Mesh(rookBody, new THREE.MeshPhongMaterial({ color: color }));
            rookBodyObj.position.y = 0.6;
            
            // Battlements (4 pieces)
            for (let i = 0; i < 4; i++) {
                const battlement = new THREE.BoxGeometry(0.15, 0.2, 0.15);
                const battlementObj = new THREE.Mesh(battlement, new THREE.MeshPhongMaterial({ color: color }));
                
                const angle = i * Math.PI / 2;
                const distance = 0.15;
                battlementObj.position.x = Math.cos(angle) * distance;
                battlementObj.position.z = Math.sin(angle) * distance;
                battlementObj.position.y = 1;
                
                group.add(battlementObj);
            }
            
            group.add(rookBaseObj);
            group.add(rookBodyObj);
            break;
            
        case 'knight':
            // Knight: horse shape
            const knightBase = new THREE.CylinderGeometry(0.3, 0.35, 0.3, 16);
            const knightBaseObj = new THREE.Mesh(knightBase, new THREE.MeshPhongMaterial({ color: color }));
            knightBaseObj.position.y = 0.15;
            
            const knightBody = new THREE.CylinderGeometry(0.25, 0.3, 0.5, 16);
            const knightBodyObj = new THREE.Mesh(knightBody, new THREE.MeshPhongMaterial({ color: color }));
            knightBodyObj.position.y = 0.55;
            
            // Horse head
            const knightHead = new THREE.SphereGeometry(0.2, 16, 16);
            const knightHeadObj = new THREE.Mesh(knightHead, new THREE.MeshPhongMaterial({ color: color }));
            knightHeadObj.position.set(0.1, 0.95, 0);
            knightHeadObj.scale.set(1.5, 1, 1);
            
            // Horse ears
            const knightEar = new THREE.ConeGeometry(0.05, 0.15, 8);
            const knightEarObj = new THREE.Mesh(knightEar, new THREE.MeshPhongMaterial({ color: color }));
            knightEarObj.position.set(0.15, 1.2, 0);
            knightEarObj.rotation.z = -Math.PI / 6;
            
            group.add(knightBaseObj);
            group.add(knightBodyObj);
            group.add(knightHeadObj);
            group.add(knightEarObj);
            break;
            
        case 'bishop':
            // Bishop: tall with slit on top
            const bishopBase = new THREE.CylinderGeometry(0.3, 0.35, 0.3, 16);
            const bishopBaseObj = new THREE.Mesh(bishopBase, new THREE.MeshPhongMaterial({ color: color }));
            bishopBaseObj.position.y = 0.15;
            
            const bishopBody = new THREE.CylinderGeometry(0.15, 0.3, 0.8, 16);
            const bishopBodyObj = new THREE.Mesh(bishopBody, new THREE.MeshPhongMaterial({ color: color }));
            bishopBodyObj.position.y = 0.7;
            
            const bishopTop = new THREE.SphereGeometry(0.15, 16, 16);
            const bishopTopObj = new THREE.Mesh(bishopTop, new THREE.MeshPhongMaterial({ color: color }));
            bishopTopObj.position.y = 1.2;
            
            // Cross on top
            const crossVert = new THREE.BoxGeometry(0.05, 0.2, 0.05);
            const crossVertObj = new THREE.Mesh(crossVert, new THREE.MeshPhongMaterial({ color: color }));
            crossVertObj.position.y = 1.45;
            
            const crossHorz = new THREE.BoxGeometry(0.15, 0.05, 0.05);
            const crossHorzObj = new THREE.Mesh(crossHorz, new THREE.MeshPhongMaterial({ color: color }));
            crossHorzObj.position.y = 1.4;
            
            group.add(bishopBaseObj);
            group.add(bishopBodyObj);
            group.add(bishopTopObj);
            group.add(crossVertObj);
            group.add(crossHorzObj);
            break;
            
        case 'queen':
            // Queen: tall with crown
            const queenBase = new THREE.CylinderGeometry(0.35, 0.4, 0.3, 16);
            const queenBaseObj = new THREE.Mesh(queenBase, new THREE.MeshPhongMaterial({ color: color }));
            queenBaseObj.position.y = 0.15;
            
            const queenBody = new THREE.CylinderGeometry(0.25, 0.35, 0.9, 16);
            const queenBodyObj = new THREE.Mesh(queenBody, new THREE.MeshPhongMaterial({ color: color }));
            queenBodyObj.position.y = 0.75;
            
            const queenTop = new THREE.SphereGeometry(0.25, 16, 16);
            const queenTopObj = new THREE.Mesh(queenTop, new THREE.MeshPhongMaterial({ color: color }));
            queenTopObj.position.y = 1.3;
            
            // Crown: 5 spikes
            for (let i = 0; i < 5; i++) {
                const spike = new THREE.ConeGeometry(0.05, 0.2, 8);
                const spikeObj = new THREE.Mesh(spike, new THREE.MeshPhongMaterial({ color: color }));
                
                const angle = i * Math.PI * 2 / 5;
                const distance = 0.15;
                spikeObj.position.x = Math.cos(angle) * distance;
                spikeObj.position.z = Math.sin(angle) * distance;
                spikeObj.position.y = 1.55;
                
                group.add(spikeObj);
            }
            
            group.add(queenBaseObj);
            group.add(queenBodyObj);
            group.add(queenTopObj);
            break;
            
        case 'king':
            // King: tall with cross
            const kingBase = new THREE.CylinderGeometry(0.35, 0.4, 0.3, 16);
            const kingBaseObj = new THREE.Mesh(kingBase, new THREE.MeshPhongMaterial({ color: color }));
            kingBaseObj.position.y = 0.15;
            
            const kingBody = new THREE.CylinderGeometry(0.3, 0.35, 1, 16);
            const kingBodyObj = new THREE.Mesh(kingBody, new THREE.MeshPhongMaterial({ color: color }));
            kingBodyObj.position.y = 0.8;
            
            const kingTop = new THREE.SphereGeometry(0.3, 16, 16);
            const kingTopObj = new THREE.Mesh(kingTop, new THREE.MeshPhongMaterial({ color: color }));
            kingTopObj.position.y = 1.4;
            
            // Cross on top (larger than bishop)
            const kingCrossVert = new THREE.BoxGeometry(0.08, 0.3, 0.08);
            const kingCrossVertObj = new THREE.Mesh(kingCrossVert, new THREE.MeshPhongMaterial({ color: color }));
            kingCrossVertObj.position.y = 1.75;
            
            const kingCrossHorz = new THREE.BoxGeometry(0.25, 0.08, 0.08);
            const kingCrossHorzObj = new THREE.Mesh(kingCrossHorz, new THREE.MeshPhongMaterial({ color: color }));
            kingCrossHorzObj.position.y = 1.65;
            
            group.add(kingBaseObj);
            group.add(kingBodyObj);
            group.add(kingTopObj);
            group.add(kingCrossVertObj);
            group.add(kingCrossHorzObj);
            break;
            
        default:
            // Default piece if type doesn't match
            const defaultGeom = new THREE.CylinderGeometry(0.3, 0.3, height, 32);
            const defaultObj = new THREE.Mesh(defaultGeom, new THREE.MeshPhongMaterial({ color: color }));
            defaultObj.position.y = height / 2;
            group.add(defaultObj);
    }
    
    // Add shadow to all pieces
    group.traverse(child => {
        if (child.isMesh) {
            child.castShadow = true;
            child.receiveShadow = true;
        }
    });
    
    return group;
}

// Create chess board with better proportions and more contrast
function createChessBoard() {
    var boardGroup = new THREE.Group();
    boardGroup.name = 'chessBoard';
    var squareSize = 1;
    
    // Create a more elegant table
    var tableTopGeometry = new THREE.BoxGeometry(14, 0.6, 14);
    var tableMaterial = new THREE.MeshPhongMaterial({ 
        color: 0x3E2723, // Daha koyu kahverengi masa rengi
        shininess: 60,
        specular: 0x333333
    });
    var tableTop = new THREE.Mesh(tableTopGeometry, tableMaterial);
    tableTop.position.set(0, -0.5, 0);
    tableTop.receiveShadow = true;
    tableTop.castShadow = true;
    boardGroup.add(tableTop);
    
    // Add decorative edge to table
    var edgeGeometry = new THREE.BoxGeometry(14.4, 0.2, 14.4);
    var edgeMaterial = new THREE.MeshPhongMaterial({
        color: 0x2E1810, // Daha da koyu kenar rengi
        shininess: 70,
        specular: 0x444444
    });
    var tableEdge = new THREE.Mesh(edgeGeometry, edgeMaterial);
    tableEdge.position.set(0, -0.2, 0);
    tableEdge.receiveShadow = true;
    tableEdge.castShadow = true;
    boardGroup.add(tableEdge);
    
    // Create table legs with more detail
    var legPositions = [
        [-6, -3.5, -6],
        [-6, -3.5, 6],
        [6, -3.5, -6],
        [6, -3.5, 6]
    ];
    
    legPositions.forEach(pos => {
        // Main leg
        var legGeometry = new THREE.BoxGeometry(0.8, 6, 0.8);
        var leg = new THREE.Mesh(legGeometry, tableMaterial);
        leg.position.set(pos[0], pos[1], pos[2]);
        leg.receiveShadow = true;
        leg.castShadow = true;
        boardGroup.add(leg);
        
        // Leg decoration
        var legTopGeometry = new THREE.BoxGeometry(1, 0.2, 1);
        var legTop = new THREE.Mesh(legTopGeometry, edgeMaterial);
        legTop.position.set(pos[0], pos[1] + 3, pos[2]);
        legTop.receiveShadow = true;
        legTop.castShadow = true;
        boardGroup.add(legTop);
    });
    
    // Create chairs for both players
    var chairPositions = [
        [0, -4, 8], // White player's chair
        [0, -4, -8]  // Black player's chair
    ];
    
    chairPositions.forEach((pos, index) => {
        // Chair seat
        var seatGeometry = new THREE.BoxGeometry(3, 0.4, 3);
        var chairMaterial = new THREE.MeshPhongMaterial({
            color: 0x4E342E,
            shininess: 50,
            specular: 0x222222
        });
        var seat = new THREE.Mesh(seatGeometry, chairMaterial);
        seat.position.set(pos[0], pos[1], pos[2]);
        seat.receiveShadow = true;
        seat.castShadow = true;
        boardGroup.add(seat);
        
        // Chair backrest - Yönü düzeltildi
        var backrestGeometry = new THREE.BoxGeometry(3, 4, 0.4);
        var backrest = new THREE.Mesh(backrestGeometry, chairMaterial);
        // index === 0 beyaz oyuncu (alt), index === 1 siyah oyuncu (üst)
        backrest.position.set(pos[0], pos[1] + 2, pos[2] + (index === 0 ? 1.5 : -1.5));
        backrest.receiveShadow = true;
        backrest.castShadow = true;
        boardGroup.add(backrest);
        
        // Chair legs
        var chairLegPositions = [
            [-1.2, -1.8, -1.2],
            [-1.2, -1.8, 1.2],
            [1.2, -1.8, -1.2],
            [1.2, -1.8, 1.2]
        ];
        
        chairLegPositions.forEach(legPos => {
            var chairLegGeometry = new THREE.BoxGeometry(0.4, 3.2, 0.4);
            var chairLeg = new THREE.Mesh(chairLegGeometry, chairMaterial);
            chairLeg.position.set(
                pos[0] + legPos[0],
                pos[1] + legPos[1],
                pos[2] + legPos[2]
            );
            chairLeg.receiveShadow = true;
            chairLeg.castShadow = true;
            boardGroup.add(chairLeg);
        });
    });
    
    // Create a border around the board
    var borderGeometry = new THREE.BoxGeometry(10, 0.2, 10);
    var borderMaterial = new THREE.MeshPhongMaterial({ 
        color: gameSettings.boardBorderColor, 
        shininess: 30,
        specular: 0x222222
    });
    var border = new THREE.Mesh(borderGeometry, borderMaterial);
    border.position.set(0, -0.15, 0);
    border.receiveShadow = true;
    border.userData.isBorder = true;
    boardGroup.add(border);
    
    // Add captured pieces pockets
    const pockets = createCapturedPiecesPockets();
    boardGroup.add(pockets);
    
    // Create the checkered board
    for (var i = 0; i < 8; i++) {
        for (var j = 0; j < 8; j++) {
            var geometry = new THREE.BoxGeometry(squareSize, 0.1, squareSize);
            // Alternating colors based on settings
            var isLight = (i + j) % 2 === 0;
            var material = new THREE.MeshPhongMaterial({
                color: isLight ? gameSettings.boardLightColor : gameSettings.boardDarkColor, 
                shininess: isLight ? 20 : 30,
                specular: 0x222222,
                side: THREE.DoubleSide
            });
            var square = new THREE.Mesh(geometry, material);
            square.position.set(i * squareSize - 3.5, -0.05, j * squareSize - 3.5);
            // Store board position for later use
            square.userData.boardPosition = {col: i, row: j};
            square.receiveShadow = true;
            boardGroup.add(square);
            
            // Add coordinate labels if enabled
            if (gameSettings.showCoordinates) {
                if (i === 0) {
                    // Add row numbers (1-8)
                    addLabel(boardGroup, (8-j).toString(), -4.3, 0, j - 3.5);
                }
                if (j === 7) {
                    // Add column letters (a-h)
                    addLabel(boardGroup, String.fromCharCode(97 + i), i - 3.5, 0, 4.3);
                }
            }
        }
    }
    
    return boardGroup;
}

// Add coordinate labels to the board
function addLabel(board, text, x, y, z) {
    // Create 2D canvas for the text
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    canvas.width = 64;
    canvas.height = 64;
    context.fillStyle = '#FFFFFF';
    context.font = 'Bold 40px Arial';
    context.textAlign = 'center';
    context.textBaseline = 'middle';
    context.fillText(text, 32, 32);
    
    // Use the canvas as a texture
    var texture = new THREE.CanvasTexture(canvas);
    var material = new THREE.MeshBasicMaterial({
        map: texture,
        transparent: true,
        side: THREE.DoubleSide
    });
    
    // Create a plane for the label
    var geometry = new THREE.PlaneGeometry(0.5, 0.5);
    var label = new THREE.Mesh(geometry, material);
    label.position.set(x, y, z);
    label.rotation.x = -Math.PI / 2;
    label.userData.isCoordinate = true;
    board.add(label);
}

function createAllPieces(boardPosition) {
    var pieces = new THREE.Group();
    var squareSize = 1;

    // White pieces
    // White back row (row 0): Rook, Knight, Bishop, Queen, King, Bishop, Knight, Rook
    pieces.add(positionPiece(createChessPiece('rook', 'white'), 0, 0, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('knight', 'white'), 1, 0, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('bishop', 'white'), 2, 0, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('queen', 'white'), 3, 0, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('king', 'white'), 4, 0, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('bishop', 'white'), 5, 0, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('knight', 'white'), 6, 0, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('rook', 'white'), 7, 0, squareSize, boardPosition));

    // White pawns (row 1)
    for (var i = 0; i < 8; i++) {
         pieces.add(positionPiece(createChessPiece('pawn', 'white'), i, 1, squareSize, boardPosition));
    }
    
    // Black pieces
    // Black back row (row 7): Rook, Knight, Bishop, Queen, King, Bishop, Knight, Rook
    pieces.add(positionPiece(createChessPiece('rook', 'black'), 0, 7, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('knight', 'black'), 1, 7, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('bishop', 'black'), 2, 7, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('queen', 'black'), 3, 7, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('king', 'black'), 4, 7, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('bishop', 'black'), 5, 7, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('knight', 'black'), 6, 7, squareSize, boardPosition));
    pieces.add(positionPiece(createChessPiece('rook', 'black'), 7, 7, squareSize, boardPosition));

    // Black pawns (row 6)
    for (var i = 0; i < 8; i++) {
         pieces.add(positionPiece(createChessPiece('pawn', 'black'), i, 6, squareSize, boardPosition));
    }
    
    return pieces;
}

// Create a chess piece using the loaded models
function createChessPiece(type, color) {
    // Clone the model
    var piece = pieceModels[color][type].clone();
    
    // Set metadata
    piece.userData.type = type;
    piece.userData.color = color;
    piece.userData.initialY = 0;
    piece.userData.hasMoved = false; // Track if the piece has moved (for castling)
    
    // Create unique ID for the piece to track movement
    const pieceId = `${color}_${type}_${Math.random().toString(36).substr(2, 9)}`;
    piece.userData.id = pieceId;
    pieceHasMoved[pieceId] = false;
    
    return piece;
}

// Update the board state array based on current piece positions
function updateBoardState() {
    // Reset board
    board = Array(8).fill().map(() => Array(8).fill(null));
    
    // Add pieces to board array
    pieces.children.forEach(piece => {
        var col = piece.userData.col;
        var row = piece.userData.row;
        if (col >= 0 && col < 8 && row >= 0 && row < 8) {
            board[row][col] = {
                type: piece.userData.type,
                color: piece.userData.color,
                piece: piece
            };
        }
    });
    
    console.log('Updated board state:', board);
}

// Check if a move is valid for a piece
function isValidMove(piece, toCol, toRow) {
    var fromCol = piece.userData.col;
    var fromRow = piece.userData.row;
    var pieceType = piece.userData.type;
    var pieceColor = piece.userData.color;
    
    // Can't move to the same position
    if (fromCol === toCol && fromRow === toRow) {
        return false;
    }
    
    // Can't capture own pieces
    if (board[toRow][toCol] && board[toRow][toCol].color === pieceColor) {
        return false;
    }
    
    // Special case for castling
    if (pieceType === 'king' && Math.abs(fromCol - toCol) === 2 && fromRow === toRow) {
        return canCastle(piece, toCol);
    }
    
    // Check basic movement rules
    let validBasicMove = false;
    
    // Implement movement rules for each piece type
    switch(pieceType) {
        case 'pawn':
            validBasicMove = isValidPawnMove(fromCol, fromRow, toCol, toRow, pieceColor);
            break;
        case 'rook':
            validBasicMove = isValidRookMove(fromCol, fromRow, toCol, toRow);
            break;
        case 'knight':
            validBasicMove = isValidKnightMove(fromCol, fromRow, toCol, toRow);
            break;
        case 'bishop':
            validBasicMove = isValidBishopMove(fromCol, fromRow, toCol, toRow);
            break;
        case 'queen':
            validBasicMove = isValidQueenMove(fromCol, fromRow, toCol, toRow);
            break;
        case 'king':
            validBasicMove = isValidKingMove(fromCol, fromRow, toCol, toRow);
            break;
        default:
            return false;
    }
    
    if (!validBasicMove) {
        return false;
    }
    
    // Check if move would leave player in check
    if (wouldBeInCheck(piece, toCol, toRow)) {
        return false;
    }
    
    return true;
}

function isValidPawnMove(fromCol, fromRow, toCol, toRow, color) {
    var direction = (color === 'white') ? 1 : -1; // White pawns move up, black pawns move down
    var startingRow = (color === 'white') ? 1 : 6; // Starting row for pawns
    
    // Forward move (1 square)
    if (fromCol === toCol && toRow === fromRow + direction && !board[toRow][toCol]) {
        return true;
    }
    
    // Forward move (2 squares from starting position)
    if (fromCol === toCol && fromRow === startingRow && toRow === fromRow + 2 * direction &&
        !board[fromRow + direction][toCol] && !board[toRow][toCol]) {
        return true;
    }
    
    // Regular capture move (diagonal)
    if (Math.abs(fromCol - toCol) === 1 && toRow === fromRow + direction && 
        board[toRow][toCol] && board[toRow][toCol].color !== color) {
        return true;
    }
    
    // En passant capture
    if (enPassantTarget && 
        Math.abs(fromCol - toCol) === 1 && 
        toRow === fromRow + direction && 
        toCol === enPassantTarget.col && 
        fromRow === enPassantTarget.row) {
        return true;
    }
    
    return false;
}

function isValidRookMove(fromCol, fromRow, toCol, toRow) {
    // Rooks move horizontally or vertically
    if (fromCol !== toCol && fromRow !== toRow) {
        return false;
    }
    
    // Check if the path is clear
    if (fromCol === toCol) { // Vertical move
        var start = Math.min(fromRow, toRow) + 1;
        var end = Math.max(fromRow, toRow);
        for (var row = start; row < end; row++) {
            if (board[row][fromCol]) {
                return false; // Path blocked
            }
        }
    } else { // Horizontal move
        var start = Math.min(fromCol, toCol) + 1;
        var end = Math.max(fromCol, toCol);
        for (var col = start; col < end; col++) {
            if (board[fromRow][col]) {
                return false; // Path blocked
            }
        }
    }
    
    return true;
}

function isValidKnightMove(fromCol, fromRow, toCol, toRow) {
    // Knights move in an L-shape: 2 squares in one direction and 1 square perpendicular
    var colDiff = Math.abs(toCol - fromCol);
    var rowDiff = Math.abs(toRow - fromRow);
    
    return (colDiff === 1 && rowDiff === 2) || (colDiff === 2 && rowDiff === 1);
}

function isValidBishopMove(fromCol, fromRow, toCol, toRow) {
    // Bishops move diagonally
    var colDiff = Math.abs(toCol - fromCol);
    var rowDiff = Math.abs(toRow - fromRow);
    
    if (colDiff !== rowDiff) {
        return false;
    }
    
    // Check if the path is clear
    var colDirection = (toCol > fromCol) ? 1 : -1;
    var rowDirection = (toRow > fromRow) ? 1 : -1;
    
    for (var i = 1; i < colDiff; i++) {
        var col = fromCol + i * colDirection;
        var row = fromRow + i * rowDirection;
        if (board[row][col]) {
            return false; // Path blocked
        }
    }
    
    return true;
}

function isValidQueenMove(fromCol, fromRow, toCol, toRow) {
    // Queens can move like a rook or bishop
    return isValidRookMove(fromCol, fromRow, toCol, toRow) || 
           isValidBishopMove(fromCol, fromRow, toCol, toRow);
}

function isValidKingMove(fromCol, fromRow, toCol, toRow) {
    // Kings move one square in any direction
    var colDiff = Math.abs(toCol - fromCol);
    var rowDiff = Math.abs(toRow - fromRow);
    
    return colDiff <= 1 && rowDiff <= 1;
}

// Check if a move would leave the player in check
function wouldBeInCheck(piece, toCol, toRow) {
    const fromCol = piece.userData.col;
    const fromRow = piece.userData.row;
    const pieceColor = piece.userData.color;
    
    // Save current board state
    const capturedPiece = board[toRow][toCol] ? board[toRow][toCol].piece : null;
    
    // Temporarily update board for check test
    const originalBoardState = JSON.parse(JSON.stringify(board.map(row => 
        row.map(cell => cell ? { color: cell.color, type: cell.type } : null)
    )));
    
    // Remove piece from original position
    board[fromRow][fromCol] = null;
    
    // If there's a piece at destination, temporarily remove it
    if (board[toRow][toCol]) {
        // We're not actually removing the piece from the scene here,
        // just updating the board state for check testing
        board[toRow][toCol] = null;
    }
    
    // Place piece at new position
    board[toRow][toCol] = {
        type: piece.userData.type,
        color: pieceColor,
        piece: piece
    };
    
    // Check if king is in check after this move
    const inCheck = isInCheck(pieceColor);
    
    // Restore original board state
    for (let row = 0; row < 8; row++) {
        for (let col = 0; col < 8; col++) {
            if (originalBoardState[row][col]) {
                board[row][col] = {
                    type: originalBoardState[row][col].type,
                    color: originalBoardState[row][col].color,
                    piece: (row === fromRow && col === fromCol) ? piece : 
                           (row === toRow && col === toCol && capturedPiece) ? capturedPiece : 
                           board[row][col] ? board[row][col].piece : null
                };
            } else {
                board[row][col] = null;
            }
        }
    }
    
    return inCheck;
}

// Check if a color is in check
function isInCheck(color) {
    // Find the king's position
    let kingRow = -1;
    let kingCol = -1;
    
    for (let row = 0; row < 8; row++) {
        for (let col = 0; col < 8; col++) {
            if (board[row][col] && 
                board[row][col].type === 'king' && 
                board[row][col].color === color) {
                kingRow = row;
                kingCol = col;
                break;
            }
        }
        if (kingRow !== -1) break;
    }
    
    if (kingRow === -1) {
        console.error('King not found on board!');
        return false;
    }
    
    // Check if any opponent piece can capture the king
    for (let row = 0; row < 8; row++) {
        for (let col = 0; col < 8; col++) {
            if (board[row][col] && board[row][col].color !== color) {
                const opponentPiece = board[row][col];
                
                // Check if opponent can move to king's position
                // using basic move checks without the check validation
                switch (opponentPiece.type) {
                    case 'pawn':
                        if (isValidPawnCapture(col, row, kingCol, kingRow, opponentPiece.color)) {
                            return true;
                        }
                        break;
                    case 'rook':
                        if (isValidRookMove(col, row, kingCol, kingRow)) {
                            return true;
                        }
                        break;
                    case 'knight':
                        if (isValidKnightMove(col, row, kingCol, kingRow)) {
                            return true;
                        }
                        break;
                    case 'bishop':
                        if (isValidBishopMove(col, row, kingCol, kingRow)) {
                            return true;
                        }
                        break;
                    case 'queen':
                        if (isValidQueenMove(col, row, kingCol, kingRow)) {
                            return true;
                        }
                        break;
                    case 'king':
                        // Kings can't directly threaten each other if they're one square apart
                        const colDiff = Math.abs(col - kingCol);
                        const rowDiff = Math.abs(row - kingRow);
                        if (colDiff <= 1 && rowDiff <= 1) {
                            return true;
                        }
                        break;
                }
            }
        }
    }
    
    return false;
}

// Helper for pawn captures (specifically for check testing)
function isValidPawnCapture(fromCol, fromRow, toCol, toRow, color) {
    const direction = (color === 'white') ? 1 : -1;
    
    // Pawns capture diagonally forward
    return Math.abs(fromCol - toCol) === 1 && 
           toRow === fromRow + direction;
}

// Check if the current player is in checkmate
function isInCheckmate(color) {
    // If not in check, can't be in checkmate
    if (!isInCheck(color)) {
        return false;
    }
    
    // Try every possible move for every piece of current color
    for (let row = 0; row < 8; row++) {
        for (let col = 0; col < 8; col++) {
            if (board[row][col] && board[row][col].color === color) {
                const piece = board[row][col].piece;
                
                // Try moving this piece to every square on the board
                for (let toRow = 0; toRow < 8; toRow++) {
                    for (let toCol = 0; toCol < 8; toCol++) {
                        // If we can make a valid move that gets us out of check
                        if (isValidMove(piece, toCol, toRow)) {
                            return false; // Not in checkmate
                        }
                    }
                }
            }
        }
    }
    
    // If we've tried all moves and none get us out of check, it's checkmate
    return true;
}

// Helper function to position a piece on the board grid
function positionPiece(piece, col, row, squareSize, boardPosition) {
    // Center the piece on the board
    piece.position.x = col - 3.5;
    piece.position.z = row - 3.5;
    piece.userData.col = col;
    piece.userData.row = row;
    return piece;
}

// Create visual indicators for valid moves
function showValidMoves(piece) {
    // Clear previous indicators
    clearValidMoveIndicators();
    
    // Create indicators for valid moves
    for (var row = 0; row < 8; row++) {
        for (var col = 0; col < 8; col++) {
            if (isValidMove(piece, col, row)) {
                var indicator = createValidMoveIndicator(col, row, scene.children[0].position, board[row][col] !== null);
                scene.add(indicator);
                validMoveIndicators.push(indicator);
            }
        }
    }
}

// Remove all valid move indicators
function clearValidMoveIndicators() {
    validMoveIndicators.forEach(indicator => {
        scene.remove(indicator);
    });
    validMoveIndicators = [];
}

// Create a single valid move indicator
function createValidMoveIndicator(col, row, boardPosition, isCapture) {
    var geometry;
    var material;
    
    if (isCapture) {
        // For captures, use a more visible red ring
        geometry = new THREE.RingGeometry(0.3, 0.45, 32);
        material = new THREE.MeshBasicMaterial({ 
            color: 0xff3333, 
            transparent: true, 
            opacity: 0.8, 
            side: THREE.DoubleSide 
        });
    } else {
        // For regular moves, use a more visible green circle
        geometry = new THREE.CircleGeometry(0.25, 32);
        material = new THREE.MeshBasicMaterial({ 
            color: 0x33ff33, 
            transparent: true, 
            opacity: 0.6, 
            side: THREE.DoubleSide 
        });
    }
    
    var indicator = new THREE.Mesh(geometry, material);
    indicator.position.x = col - 3.5;  // Adjust for board center
    indicator.position.y = 0.02;       // Slightly above the board
    indicator.position.z = row - 3.5;  // Adjust for board center
    indicator.rotation.x = -Math.PI / 2; // Lay flat on the board
    indicator.userData = { col: col, row: row };
    return indicator;
}

function onMouseDown(event) {
    event.preventDefault();
    
    // Only allow interaction if models are loaded
    if (!modelsLoaded) return;
    
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = - (event.clientY / window.innerHeight) * 2 + 1;
    
    raycaster.setFromCamera(mouse, camera);
    
    // Check if we're clicking on a valid move indicator
    var indicatorIntersects = raycaster.intersectObjects(validMoveIndicators);
    if (indicatorIntersects.length > 0 && selectedObject) {
        var targetPosition = indicatorIntersects[0].object.userData;
        movePiece(selectedObject, targetPosition.col, targetPosition.row);
        clearValidMoveIndicators();
        selectedObject = null;
        return;
    }
    
    // Check if we're clicking on a piece
    var pieceIntersects = raycaster.intersectObjects(pieces.children, true);
    if (pieceIntersects.length > 0) {
        // Find the top-level piece object
        var intersectedObject = pieceIntersects[0].object;
        var pieceObject = intersectedObject;
        
        // Traverse up to find the top-level piece
        while (pieceObject.parent && pieceObject.parent !== pieces) {
            pieceObject = pieceObject.parent;
        }
        
        // Only allow selecting pieces of the current turn
        if (pieceObject.userData.color !== currentTurn) {
            clearValidMoveIndicators();
            if (selectedObject) {
                // Reset highlight on previously selected piece
                selectedObject.traverse(child => {
                    if (child.isMesh && child.material && child.userData.originalEmissive !== undefined) {
                        child.material.emissive.set(child.userData.originalEmissive);
                    }
                });
            }
            selectedObject = null;
            return;
        }
        
        // If we're clicking on the same piece again, deselect it
        if (selectedObject === pieceObject) {
            clearValidMoveIndicators();
            selectedObject.traverse(child => {
                if (child.isMesh && child.material && child.userData.originalEmissive !== undefined) {
                    child.material.emissive.set(child.userData.originalEmissive);
                }
            });
            selectedObject = null;
            return;
        }
        
        // If we had a previously selected piece, reset its highlight
        if (selectedObject) {
            selectedObject.traverse(child => {
                if (child.isMesh && child.material && child.userData.originalEmissive !== undefined) {
                    child.material.emissive.set(child.userData.originalEmissive);
                }
            });
        }
        
        // Select the new piece
        selectedObject = pieceObject;
        
        // Show valid moves for the selected piece
        showValidMoves(selectedObject);
        
        // Set highlight by traversing through all meshes in the group
        selectedObject.traverse(child => {
            if (child.isMesh && child.material) {
                if (child.userData.originalEmissive === undefined) {
                    child.userData.originalEmissive = child.material.emissive.getHex();
                }
                child.material.emissive.set(0xffff00);
            }
        });
        
        return;
    }
    
    // If we click on an empty area and had a selection, deselect it
    if (selectedObject) {
        clearValidMoveIndicators();
        selectedObject.traverse(child => {
            if (child.isMesh && child.material && child.userData.originalEmissive !== undefined) {
                child.material.emissive.set(child.userData.originalEmissive);
            }
        });
        selectedObject = null;
    }
}

function onMouseMove(event) {
    event.preventDefault();
    
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = - (event.clientY / window.innerHeight) * 2 + 1;
    
    raycaster.setFromCamera(mouse, camera);
    var intersection = new THREE.Vector3();
    if (raycaster.ray.intersectPlane(dragPlane, intersection)) {
        // Update piece position, keeping its original Y
        selectedObject.position.x = intersection.x - offset.x;
        selectedObject.position.z = intersection.z - offset.z;
    }
}

function onMouseUp(event) {
    document.removeEventListener('mousemove', onMouseMove, false);
    document.removeEventListener('mouseup', onMouseUp, false);
    
    if (!selectedObject) return;
    
    // Snap to grid and check if move is valid
    var boardSize = 8;
    var squareSize = 1;
    var boardOrigin = scene.children[0].position;
    
    // Calculate the grid position based on piece position
    var gridX = Math.round((selectedObject.position.x - boardOrigin.x) / squareSize);
    var gridZ = Math.round((selectedObject.position.z - boardOrigin.z) / squareSize);
    
    // Ensure the grid position is within bounds
    gridX = Math.max(0, Math.min(boardSize - 1, gridX));
    gridZ = Math.max(0, Math.min(boardSize - 1, gridZ));
    
    // Check if this is a valid move
    if (isValidMove(selectedObject, gridX, gridZ)) {
        // If there's a piece at the destination, remove it (capture)
        if (board[gridZ][gridX]) {
            var capturedPiece = board[gridZ][gridX].piece;
            pieces.remove(capturedPiece);
        }
        
        // Update position
        selectedObject.position.x = boardOrigin.x + gridX * squareSize;
        selectedObject.position.z = boardOrigin.z + gridZ * squareSize;
        // Make sure Y position is reset back to proper height
        selectedObject.position.y = selectedObject.userData.originalY || 0;
        selectedObject.userData.col = gridX;
        selectedObject.userData.row = gridZ;
        
        // Update board state
        updateBoardState();
        
        // Switch turns
        currentTurn = (currentTurn === 'white') ? 'black' : 'white';
        
        // Update info display
        updateGameInfo();
    } else {
        // Move is invalid, return to original position
        var origCol = selectedObject.userData.col;
        var origRow = selectedObject.userData.row;
        selectedObject.position.x = boardOrigin.x + origCol * squareSize;
        selectedObject.position.z = boardOrigin.z + origRow * squareSize;
        // Make sure Y position is reset back to proper height
        selectedObject.position.y = selectedObject.userData.originalY || 0;
    }
    
    // Clear valid move indicators
    clearValidMoveIndicators();
    
    // Reset highlight by traversing all meshes
    if (selectedObject) {
        selectedObject.traverse(child => {
            if (child.isMesh && child.material) {
                if (child.userData.originalEmissive !== undefined) {
                    // Make a clean reset of emissive property
                    if (typeof child.userData.originalEmissive === 'number') {
                        child.material.emissive.setHex(child.userData.originalEmissive);
                    } else {
                        child.material.emissive.set(0x000000); // Default to black if original is undefined
                    }
                }
            }
        });
    }
    
    selectedObject = null;
}

// Function to move a piece to a new position
function movePiece(piece, col, row) {
    const pieceType = piece.userData.type;
    const color = piece.userData.color;
    const fromCol = piece.userData.col;
    const fromRow = piece.userData.row;
    
    // Check for pawn promotion
    if (pieceType === 'pawn' && ((color === 'white' && row === 7) || (color === 'black' && row === 0))) {
        // Play promotion sound when showing dialog
        playSound('promote');
        // Set up promotion
        promotionInProgress = { pawn: piece, col, row };
        
        // Save the last move (will be updated after promotion)
        lastMove = {
            piece: pieceType,
            color: color,
            fromCol: fromCol,
            fromRow: fromRow,
            toCol: col,
            toRow: row,
            capturedPiece: board[row][col] ? board[row][col].type : null,
            promotion: true
        };
        
        // If there's a piece at the destination, capture it
        if (board[row][col]) {
            var capturedPiece = board[row][col].piece;
            const capturedColor = capturedPiece.userData.color;
            
            // Add to captured pieces array
            capturedPieces[color].push(board[row][col].type);
            
            // Remove captured piece from the board in the game state
            scene.remove(capturedPiece);
            
            // Move captured piece to the appropriate pocket based on which player captured it
            const pocketName = color === 'white' ? 'whitePocket' : 'blackPocket';
            const pocket = scene.getObjectByName(pocketName);
            
            if (pocket) {
                // Position the captured piece in the pocket
                const pocketPieces = capturedPieces[color];
                const pocketRow = Math.floor((pocketPieces.length - 1) / 4);
                const pocketCol = (pocketPieces.length - 1) % 4;
                
                // Scale down the captured piece
                capturedPiece.scale.set(0.5, 0.5, 0.5);
                
                if (capturedPiece.userData.color === 'white') {
                    // White pieces in black's pocket (left side)
                    capturedPiece.position.x = -5.5 + (pocketCol * 0.8 - 1.2);
                    capturedPiece.position.z = -1.5 + pocketRow * 0.8;
                    capturedPiece.rotation.y = Math.PI / 2; // Face right
                } else {
                    // Black pieces in white's pocket (right side)
                    capturedPiece.position.x = 5.5 - (pocketCol * 0.8 - 1.2);
                    capturedPiece.position.z = -1.5 + pocketRow * 0.8;
                    capturedPiece.rotation.y = -Math.PI / 2; // Face left
                }
                
                capturedPiece.position.y = 0.2 + (pocketRow * 0.2);
                scene.add(capturedPiece);
            }
            
            updateCapturedPiecesPanel();
        } else {
            // Play move sound (not a capture)
            playSound('move');
        }
        
        // Update position temporarily
        piece.position.x = col - 3.5;
        piece.position.z = row - 3.5;
        piece.userData.col = col;
        piece.userData.row = row;
        
        // Update board state
        updateBoardState();
        
        // Show the promotion dialog
        showPromotionDialog();
        
        // After the move is complete, ensure the piece's emissive property is reset
        piece.traverse(child => {
            if (child.isMesh && child.material) {
                if (child.userData.originalEmissive !== undefined) {
                    // Reset emissive property
                    if (typeof child.userData.originalEmissive === 'number') {
                        child.material.emissive.setHex(child.userData.originalEmissive);
                    } else {
                        child.material.emissive.set(0x000000); // Default to black
                    }
                }
            }
        });
        
        return;
    }
    
    // Determine if move is a capture
    const isCapture = board[row][col] !== null;
    
    // Determine if move is castling
    const isCastle = pieceType === 'king' && Math.abs(fromCol - col) === 2;
    
    // Save the last move
    lastMove = {
        piece: pieceType,
        color: color,
        fromCol: fromCol,
        fromRow: fromRow,
        toCol: col,
        toRow: row,
        capturedPiece: isCapture ? board[row][col].type : null,
        isCastle: isCastle
    };
    
    // Execute the move
    if (isCastle) {
        playSound('castle');
        performCastle(piece, col);
        
        // Reset emissive property after castling
        piece.traverse(child => {
            if (child.isMesh && child.material) {
                if (child.userData.originalEmissive !== undefined) {
                    if (typeof child.userData.originalEmissive === 'number') {
                        child.material.emissive.setHex(child.userData.originalEmissive);
                    } else {
                        child.material.emissive.set(0x000000); // Default to black
                    }
                }
            }
        });
    } else {
        // Handle captures and regular moves
        if (isCapture) {
            playSound('capture');
            var capturedPiece = board[row][col].piece;
            const capturedColor = capturedPiece.userData.color;
            
            // Add to captured pieces array
            capturedPieces[color].push(board[row][col].type);
            
            // Move captured piece to the appropriate pocket
            const pocketName = color === 'white' ? 'whitePocket' : 'blackPocket';
            const pocket = scene.getObjectByName(pocketName);
            
            if (pocket) {
                // Position the captured piece in the pocket
                const pocketPieces = capturedPieces[color];
                const pocketRow = Math.floor((pocketPieces.length - 1) / 4);
                const pocketCol = (pocketPieces.length - 1) % 4;
                
                // Scale down the captured piece
                capturedPiece.scale.set(0.5, 0.5, 0.5);
                
                if (capturedPiece.userData.color === 'white') {
                    // White pieces in black's pocket (left side)
                    capturedPiece.position.x = -5.5 + (pocketCol * 0.8 - 1.2);
                    capturedPiece.position.z = -1.5 + pocketRow * 0.8;
                    capturedPiece.rotation.y = Math.PI / 2; // Face right
                } else {
                    // Black pieces in white's pocket (right side)
                    capturedPiece.position.x = 5.5 - (pocketCol * 0.8 - 1.2);
                    capturedPiece.position.z = -1.5 + pocketRow * 0.8;
                    capturedPiece.rotation.y = -Math.PI / 2; // Face left
                }
                
                capturedPiece.position.y = 0.2 + (pocketRow * 0.2);
                scene.add(capturedPiece);
            }
            
            updateCapturedPiecesPanel();
        } else {
            playSound('move');
        }
        
        // Update position
        piece.position.x = col - 3.5;
        piece.position.z = row - 3.5;
        piece.userData.col = col;
        piece.userData.row = row;
        
        // Mark the piece as moved
        pieceHasMoved[piece.userData.id] = true;
        
        // Update board state
        updateBoardState();
        
        // Reset emissive property after the move
        piece.traverse(child => {
            if (child.isMesh && child.material) {
                if (child.userData.originalEmissive !== undefined) {
                    if (typeof child.userData.originalEmissive === 'number') {
                        child.material.emissive.setHex(child.userData.originalEmissive);
                    } else {
                        child.material.emissive.set(0x000000); // Default to black
                    }
                }
            }
        });
    }
    
    // Switch turns
    currentTurn = (currentTurn === 'white') ? 'black' : 'white';
    
    // Check for check/checkmate
    isCheck = isInCheck(currentTurn);
    isCheckmate = isCheck && isInCheckmate(currentTurn);
    
    // Play appropriate sound for check/checkmate
    if (isCheckmate) {
        playSound('checkmate');
    } else if (isCheck) {
        playSound('check');
    }
    
    // Create algebraic notation for the move
    const notation = getMoveNotation(
        pieceType, 
        fromCol, 
        fromRow, 
        col, 
        row, 
        isCapture, 
        isCheck, 
        isCheckmate, 
        isCastle,
        false,
        null
    );
    
    // Add the move to the history
    moveHistory.push({
        piece: pieceType,
        color: color,
        fromCol: fromCol,
        fromRow: fromRow,
        toCol: col,
        toRow: row,
        capturedPiece: lastMove.capturedPiece,
        isCapture: isCapture,
        isCastle: isCastle,
        isCheck: isCheck,
        isCheckmate: isCheckmate,
        notation: notation
    });
    
    // Update the move history panel
    updateMoveHistoryPanel();
    
    // Update info display
    updateGameInfo();
}

// Function to check if castling is valid
function canCastle(king, toCol) {
    const fromCol = king.userData.col;
    const row = king.userData.row;
    const color = king.userData.color;
    
    // King must not have moved yet
    if (pieceHasMoved[king.userData.id]) {
        return false;
    }
    
    // King must not be in check
    if (isInCheck(color)) {
        return false;
    }
    
    // Determine if it's kingside (right) or queenside (left) castling
    const isKingSideCastling = toCol > fromCol;
    const rookCol = isKingSideCastling ? 7 : 0;
    
    // Find the rook
    let rook = null;
    if (board[row][rookCol] && 
        board[row][rookCol].type === 'rook' && 
        board[row][rookCol].color === color) {
        rook = board[row][rookCol].piece;
    } else {
        return false; // No rook found at expected position
    }
    
    // Rook must not have moved yet
    if (pieceHasMoved[rook.userData.id]) {
        return false;
    }
    
    // Check if path between king and rook is clear
    const step = isKingSideCastling ? 1 : -1;
    let col = fromCol + step;
    const endCol = isKingSideCastling ? rookCol - 1 : rookCol + 1;
    
    while (col !== rookCol) {
        // Path must be clear
        if (board[row][col]) {
            return false;
        }
        
        // King's path must not pass through check
        if (Math.abs(col - fromCol) <= 2) {
            // Create a temporary board state to test if path is under attack
            const originalBoardState = JSON.parse(JSON.stringify(board.map(row => 
                row.map(cell => cell ? { color: cell.color, type: cell.type } : null)
            )));
            
            // Temporarily move king to this position
            board[row][fromCol] = null;
            board[row][col] = {
                type: 'king',
                color: color,
                piece: king
            };
            
            // Check if king would be in check at this position
            const inCheck = isInCheck(color);
            
            // Restore original board state
            for (let r = 0; r < 8; r++) {
                for (let c = 0; c < 8; c++) {
                    if (originalBoardState[r][c]) {
                        board[r][c] = {
                            type: originalBoardState[r][c].type,
                            color: originalBoardState[r][c].color,
                            piece: board[r][c] ? board[r][c].piece : 
                                   (r === row && c === col) ? king : 
                                   null
                        };
                    } else {
                        board[r][c] = null;
                    }
                }
            }
            
            if (inCheck) {
                return false;
            }
        }
        
        col += step;
    }
    
    return true;
}

// Function to perform castling
function performCastle(king, toCol) {
    const fromCol = king.userData.col;
    const row = king.userData.row;
    const isKingSideCastling = toCol > fromCol;
    const rookCol = isKingSideCastling ? 7 : 0;
    const newRookCol = isKingSideCastling ? toCol - 1 : toCol + 1;
    
    // Find the rook
    const rook = board[row][rookCol].piece;
    
    // Move the king
    king.position.x = toCol - 3.5;
    king.userData.col = toCol;
    
    // Move the rook
    rook.position.x = newRookCol - 3.5;
    rook.userData.col = newRookCol;
    
    // Mark both pieces as moved
    pieceHasMoved[king.userData.id] = true;
    pieceHasMoved[rook.userData.id] = true;
    
    // Update board state
    updateBoardState();
}

// Update game information display
function updateGameInfo() {
    var infoElement = document.getElementById('info');
    if (infoElement) {
        let infoText = '3D Satranç Oyunu - Sıra: ' + (currentTurn === 'white' ? 'beyaz' : 'siyah');
        
        if (isCheckmate) {
            const winner = currentTurn === 'white' ? 'siyah' : 'beyaz'; 
            infoText = `3D Satranç Oyunu - ŞAH MAT! ${winner} kazandı!`;
        } else if (isCheck) {
            infoText += ' - ŞAH!';
        }
        
        infoElement.innerHTML = infoText;
    }
}

function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
}

function animate() {
    requestAnimationFrame(animate);
    
    if (initialized) {
        controls.update();
        renderer.render(scene, camera);
    }
}

// Start initialization when window is loaded
window.addEventListener('load', init);

// Create HTML elements for promotion selection
function createPromotionUI() {
    // Create promotion container
    var promotionContainer = document.createElement('div');
    promotionContainer.id = 'promotion-container';
    promotionContainer.style.position = 'absolute';
    promotionContainer.style.top = '50%';
    promotionContainer.style.left = '50%';
    promotionContainer.style.transform = 'translate(-50%, -50%)';
    promotionContainer.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    promotionContainer.style.padding = '20px';
    promotionContainer.style.borderRadius = '10px';
    promotionContainer.style.display = 'none';
    promotionContainer.style.zIndex = '100';
    promotionContainer.style.textAlign = 'center';
    
    // Create title
    var title = document.createElement('h2');
    title.textContent = 'Piyon Terfi';
    title.style.color = 'white';
    title.style.marginBottom = '15px';
    promotionContainer.appendChild(title);
    
    // Create button container
    var buttonContainer = document.createElement('div');
    buttonContainer.style.display = 'flex';
    buttonContainer.style.justifyContent = 'center';
    buttonContainer.style.gap = '10px';
    promotionContainer.appendChild(buttonContainer);
    
    // Create piece selection buttons
    const pieces = ['queen', 'rook', 'bishop', 'knight'];
    pieces.forEach(piece => {
        var button = document.createElement('button');
        button.id = `promote-to-${piece}`;
        button.textContent = getPieceDisplayName(piece);
        button.style.padding = '10px 15px';
        button.style.margin = '5px';
        button.style.borderRadius = '5px';
        button.style.backgroundColor = '#4CAF50';
        button.style.color = 'white';
        button.style.border = 'none';
        button.style.cursor = 'pointer';
        button.style.fontSize = '16px';
        button.style.transition = 'background-color 0.3s';
        
        // Hover effect
        button.onmouseover = function() {
            this.style.backgroundColor = '#45a049';
        };
        button.onmouseout = function() {
            this.style.backgroundColor = '#4CAF50';
        };
        
        button.onclick = function() {
            promotePawn(piece);
        };
        
        buttonContainer.appendChild(button);
    });
    
    document.body.appendChild(promotionContainer);
}

// Get display name for piece type
function getPieceDisplayName(pieceType) {
    switch(pieceType) {
        case 'queen': return 'Vezir';
        case 'rook': return 'Kale';
        case 'bishop': return 'Fil';
        case 'knight': return 'At';
        default: return pieceType;
    }
}

// Show promotion dialog
function showPromotionDialog() {
    var promotionContainer = document.getElementById('promotion-container');
    if (!promotionContainer) {
        createPromotionUI();
        promotionContainer = document.getElementById('promotion-container');
    }
    promotionContainer.style.display = 'block';
}

// Hide promotion dialog
function hidePromotionDialog() {
    var promotionContainer = document.getElementById('promotion-container');
    if (promotionContainer) {
        promotionContainer.style.display = 'none';
    }
}

// Promote the pawn to selected piece
function promotePawn(newType) {
    if (!promotionInProgress) return;
    
    const { pawn, col, row } = promotionInProgress;
    const color = pawn.userData.color;
    const fromCol = lastMove.fromCol;
    const fromRow = lastMove.fromRow;
    const isCapture = lastMove.capturedPiece !== null;
    
    // Remove the pawn from the scene
    pieces.remove(pawn);
    
    // Create the new piece
    const newPiece = createChessPiece(newType, color);
    
    // Position the new piece
    newPiece.position.x = col - 3.5;
    newPiece.position.z = row - 3.5;
    newPiece.userData.col = col;
    newPiece.userData.row = row;
    
    // Add the new piece to the scene
    pieces.add(newPiece);
    
    // Update board state
    updateBoardState();
    
    // Switch turns
    currentTurn = (currentTurn === 'white') ? 'black' : 'white';
    
    // Check for check/checkmate
    isCheck = isInCheck(currentTurn);
    isCheckmate = isCheck && isInCheckmate(currentTurn);
    
    // Create algebraic notation for the promotion move
    const notation = getMoveNotation(
        'pawn', 
        fromCol, 
        fromRow, 
        col, 
        row, 
        isCapture, 
        isCheck, 
        isCheckmate, 
        false, 
        true, 
        newType
    );
    
    // Add the move to the history
    moveHistory.push({
        piece: 'pawn',
        promotedTo: newType,
        color: color,
        fromCol: fromCol,
        fromRow: fromRow,
        toCol: col,
        toRow: row,
        capturedPiece: lastMove.capturedPiece,
        isCapture: isCapture,
        isPromotion: true,
        isCheck: isCheck,
        isCheckmate: isCheckmate,
        notation: notation
    });
    
    // Update the move history panel
    updateMoveHistoryPanel();
    
    // Update game info
    updateGameInfo();
    
    // Hide the promotion dialog
    hidePromotionDialog();
    
    // Clear promotion in progress
    promotionInProgress = null;
}

// Create HTML elements for move history panel
function createMoveHistoryPanel() {
    // Create the panel container
    var historyPanel = document.createElement('div');
    historyPanel.id = 'history-panel';
    historyPanel.style.position = 'absolute';
    historyPanel.style.top = '20px';
    historyPanel.style.right = '20px';
    historyPanel.style.width = '250px';
    historyPanel.style.maxHeight = '60%';
    historyPanel.style.overflowY = 'auto';
    historyPanel.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
    historyPanel.style.color = 'white';
    historyPanel.style.borderRadius = '10px';
    historyPanel.style.padding = '15px';
    historyPanel.style.fontFamily = 'Arial, sans-serif';
    historyPanel.style.zIndex = '10';
    historyPanel.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';
    
    // Create panel title
    var title = document.createElement('h3');
    title.textContent = 'Hamle Geçmişi';
    title.style.textAlign = 'center';
    title.style.marginTop = '0';
    title.style.marginBottom = '10px';
    title.style.borderBottom = '1px solid rgba(255, 255, 255, 0.3)';
    title.style.paddingBottom = '5px';
    historyPanel.appendChild(title);
    
    // Create move list container
    var moveListContainer = document.createElement('div');
    moveListContainer.id = 'move-list';
    moveListContainer.style.fontFamily = 'monospace';
    moveListContainer.style.fontSize = '14px';
    historyPanel.appendChild(moveListContainer);
    
    document.body.appendChild(historyPanel);
}

// Update move history panel with the latest moves
function updateMoveHistoryPanel() {
    var moveListElement = document.getElementById('move-list');
    if (!moveListElement) return;
    
    // Clear current content
    moveListElement.innerHTML = '';
    
    // Create table for moves
    var table = document.createElement('table');
    table.style.width = '100%';
    table.style.borderCollapse = 'collapse';
    
    // Add header row
    var headerRow = document.createElement('tr');
    
    var moveHeader = document.createElement('th');
    moveHeader.textContent = '#';
    moveHeader.style.textAlign = 'center';
    moveHeader.style.padding = '5px';
    moveHeader.style.width = '25%';
    
    var whiteHeader = document.createElement('th');
    whiteHeader.textContent = 'Beyaz';
    whiteHeader.style.textAlign = 'center';
    whiteHeader.style.padding = '5px';
    whiteHeader.style.width = '37.5%';
    
    var blackHeader = document.createElement('th');
    blackHeader.textContent = 'Siyah';
    blackHeader.style.textAlign = 'center';
    blackHeader.style.padding = '5px';
    blackHeader.style.width = '37.5%';
    
    headerRow.appendChild(moveHeader);
    headerRow.appendChild(whiteHeader);
    headerRow.appendChild(blackHeader);
    table.appendChild(headerRow);
    
    // Group moves by pairs (white and black)
    for (let i = 0; i < moveHistory.length; i += 2) {
        const moveNumber = Math.floor(i / 2) + 1;
        const whiteMove = moveHistory[i];
        const blackMove = i + 1 < moveHistory.length ? moveHistory[i + 1] : null;
        
        const row = document.createElement('tr');
        
        // Move number
        const moveNumberCell = document.createElement('td');
        moveNumberCell.textContent = moveNumber;
        moveNumberCell.style.textAlign = 'center';
        moveNumberCell.style.padding = '3px';
        
        // White move
        const whiteMoveCell = document.createElement('td');
        whiteMoveCell.textContent = whiteMove.notation;
        whiteMoveCell.style.textAlign = 'center';
        whiteMoveCell.style.padding = '3px';
        if (i === moveHistory.length - 1) {
            whiteMoveCell.style.fontWeight = 'bold';
            whiteMoveCell.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
        }
        
        // Black move
        const blackMoveCell = document.createElement('td');
        if (blackMove) {
            blackMoveCell.textContent = blackMove.notation;
            blackMoveCell.style.textAlign = 'center';
            blackMoveCell.style.padding = '3px';
            if (i + 1 === moveHistory.length - 1) {
                blackMoveCell.style.fontWeight = 'bold';
                blackMoveCell.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
            }
        }
        
        row.appendChild(moveNumberCell);
        row.appendChild(whiteMoveCell);
        row.appendChild(blackMoveCell);
        table.appendChild(row);
    }
    
    moveListElement.appendChild(table);
    moveListElement.scrollTop = moveListElement.scrollHeight; // Auto-scroll to bottom
}

// Convert column to algebraic notation letter (0 -> a, 1 -> b, etc.)
function colToLetter(col) {
    return String.fromCharCode(97 + col);
}

// Get algebraic notation for a move
function getMoveNotation(piece, fromCol, fromRow, toCol, toRow, isCapture, isCheck, isCheckmate, isCastle, isPawnPromotion, promotionPiece) {
    if (isCastle) {
        // Kingside castling
        if (toCol > fromCol) {
            return 'O-O';
        } 
        // Queenside castling
        else {
            return 'O-O-O';
        }
    }
    
    let notation = '';
    
    // Add piece letter (except for pawns)
    if (piece !== 'pawn') {
        notation += getPieceNotationSymbol(piece);
    }
    
    // For pawn captures, add the file they came from
    if (piece === 'pawn' && isCapture) {
        notation += colToLetter(fromCol);
    }
    
    // For pieces other than pawns, we need to disambiguate in some cases
    if (piece !== 'pawn') {
        // Check if another piece of the same type can move to the same square
        const ambiguousPieces = findAmbiguousPieces(piece, fromCol, fromRow, toCol, toRow);
        
        if (ambiguousPieces.length > 0) {
            // Check if just adding the file resolves the ambiguity
            const sameFileCount = ambiguousPieces.filter(p => p.col === fromCol).length;
            const sameRankCount = ambiguousPieces.filter(p => p.row === fromRow).length;
            
            if (sameFileCount === 0) {
                // Adding the file is sufficient
                notation += colToLetter(fromCol);
            } else if (sameRankCount === 0) {
                // Adding the rank is sufficient
                notation += (fromRow + 1);
            } else {
                // Need both file and rank
                notation += colToLetter(fromCol) + (fromRow + 1);
            }
        }
    }
    
    // Add capture symbol
    if (isCapture) {
        notation += 'x';
    }
    
    // Add destination square
    notation += colToLetter(toCol) + (toRow + 1);
    
    // Add promotion piece
    if (isPawnPromotion && promotionPiece) {
        notation += '=' + getPieceNotationSymbol(promotionPiece);
    }
    
    // Add check or checkmate symbol
    if (isCheckmate) {
        notation += '#';
    } else if (isCheck) {
        notation += '+';
    }
    
    return notation;
}

// Get standard notation symbol for a piece
function getPieceNotationSymbol(pieceType) {
    switch(pieceType) {
        case 'king': return 'K';
        case 'queen': return 'Q';
        case 'rook': return 'R';
        case 'bishop': return 'B';
        case 'knight': return 'N';
        case 'pawn': return '';
        default: return '';
    }
}

// Find pieces of the same type that could also move to the target square
function findAmbiguousPieces(pieceType, fromCol, fromRow, toCol, toRow) {
    const result = [];
    const color = board[fromRow][fromCol].color;
    
    // Check all pieces of the same type and color
    for (let row = 0; row < 8; row++) {
        for (let col = 0; col < 8; col++) {
            // Skip the original piece
            if (row === fromRow && col === fromCol) continue;
            
            // Check if it's the same type and color
            if (board[row][col] && 
                board[row][col].type === pieceType && 
                board[row][col].color === color) {
                
                // Check if this piece can also move to the target square
                if (isValidMove(board[row][col].piece, toCol, toRow)) {
                    result.push({ row, col });
                }
            }
        }
    }
    
    return result;
}

// Create HTML elements for UI components
function createGameUI() {
    // Create promotion UI
    createPromotionUI();
    
    // Create move history panel
    createMoveHistoryPanel();
    
    // Create captured pieces panel
    createCapturedPiecesPanel();
    
    // Create settings panel
    createSettingsPanel();
    
    // Add game management UI
    createGameManagementUI();
}

// Create panel to display captured pieces
function createCapturedPiecesPanel() {
    // Create container for captured pieces panel
    var capturedPanel = document.createElement('div');
    capturedPanel.id = 'captured-panel';
    capturedPanel.style.position = 'absolute';
    capturedPanel.style.top = '20px';
    capturedPanel.style.left = '20px';
    capturedPanel.style.width = '250px';
    capturedPanel.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
    capturedPanel.style.color = 'white';
    capturedPanel.style.borderRadius = '10px';
    capturedPanel.style.padding = '15px';
    capturedPanel.style.fontFamily = 'Arial, sans-serif';
    capturedPanel.style.zIndex = '10';
    capturedPanel.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';
    
    // Create panel title
    var title = document.createElement('h3');
    title.textContent = 'Ele Geçirilen Taşlar';
    title.style.textAlign = 'center';
    title.style.marginTop = '0';
    title.style.marginBottom = '10px';
    title.style.borderBottom = '1px solid rgba(255, 255, 255, 0.3)';
    title.style.paddingBottom = '5px';
    capturedPanel.appendChild(title);
    
    // Container for white captures (captured black pieces)
    var whiteCaptures = document.createElement('div');
    whiteCaptures.id = 'white-captures';
    
    var whiteCapturesTitle = document.createElement('h4');
    whiteCapturesTitle.textContent = 'Beyaz Oyuncunun Aldığı Taşlar';
    whiteCapturesTitle.style.margin = '10px 0 5px 0';
    whiteCapturesTitle.style.fontSize = '14px';
    whiteCaptures.appendChild(whiteCapturesTitle);
    
    var whiteCapturesContent = document.createElement('div');
    whiteCapturesContent.id = 'white-captures-content';
    whiteCapturesContent.style.minHeight = '30px';
    whiteCapturesContent.style.padding = '5px';
    whiteCapturesContent.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
    whiteCapturesContent.style.borderRadius = '5px';
    whiteCapturesContent.style.marginBottom = '10px';
    whiteCapturesContent.style.display = 'flex';
    whiteCapturesContent.style.flexWrap = 'wrap';
    whiteCapturesContent.style.alignItems = 'center';
    whiteCaptures.appendChild(whiteCapturesContent);
    
    capturedPanel.appendChild(whiteCaptures);
    
    // Container for black captures (captured white pieces)
    var blackCaptures = document.createElement('div');
    blackCaptures.id = 'black-captures';
    
    var blackCapturesTitle = document.createElement('h4');
    blackCapturesTitle.textContent = 'Siyah Oyuncunun Aldığı Taşlar';
    blackCapturesTitle.style.margin = '10px 0 5px 0';
    blackCapturesTitle.style.fontSize = '14px';
    blackCaptures.appendChild(blackCapturesTitle);
    
    var blackCapturesContent = document.createElement('div');
    blackCapturesContent.id = 'black-captures-content';
    blackCapturesContent.style.minHeight = '30px';
    blackCapturesContent.style.padding = '5px';
    blackCapturesContent.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
    blackCapturesContent.style.borderRadius = '5px';
    blackCapturesContent.style.display = 'flex';
    blackCapturesContent.style.flexWrap = 'wrap';
    blackCapturesContent.style.alignItems = 'center';
    blackCaptures.appendChild(blackCapturesContent);
    
    capturedPanel.appendChild(blackCaptures);
    
    document.body.appendChild(capturedPanel);
}

// Update the captured pieces panel
function updateCapturedPiecesPanel() {
    // Update white captures display (black pieces captured by white)
    var whiteCapturesContent = document.getElementById('white-captures-content');
    if (whiteCapturesContent) {
        whiteCapturesContent.innerHTML = '';
        
        if (capturedPieces.white.length === 0) {
            whiteCapturesContent.textContent = 'Yok';
        } else {
            // Group captured pieces by type
            const groupedCaptures = {};
            
            capturedPieces.white.forEach(piece => {
                if (!groupedCaptures[piece]) {
                    groupedCaptures[piece] = 1;
                } else {
                    groupedCaptures[piece]++;
                }
            });
            
            // Display grouped pieces
            for (const [pieceType, count] of Object.entries(groupedCaptures)) {
                const pieceIcon = createPieceIcon(pieceType, 'black');
                pieceIcon.style.margin = '3px';
                
                // If there are multiple pieces of the same type, add a count badge
                if (count > 1) {
                    const countBadge = document.createElement('span');
                    countBadge.textContent = count;
                    countBadge.style.position = 'absolute';
                    countBadge.style.right = '-5px';
                    countBadge.style.bottom = '-5px';
                    countBadge.style.backgroundColor = '#4CAF50';
                    countBadge.style.color = 'white';
                    countBadge.style.borderRadius = '50%';
                    countBadge.style.width = '16px';
                    countBadge.style.height = '16px';
                    countBadge.style.fontSize = '12px';
                    countBadge.style.display = 'flex';
                    countBadge.style.justifyContent = 'center';
                    countBadge.style.alignItems = 'center';
                    pieceIcon.appendChild(countBadge);
                }
                
                whiteCapturesContent.appendChild(pieceIcon);
            }
        }
    }
    
    // Update black captures display (white pieces captured by black)
    var blackCapturesContent = document.getElementById('black-captures-content');
    if (blackCapturesContent) {
        blackCapturesContent.innerHTML = '';
        
        if (capturedPieces.black.length === 0) {
            blackCapturesContent.textContent = 'Yok';
        } else {
            // Group captured pieces by type
            const groupedCaptures = {};
            
            capturedPieces.black.forEach(piece => {
                if (!groupedCaptures[piece]) {
                    groupedCaptures[piece] = 1;
                } else {
                    groupedCaptures[piece]++;
                }
            });
            
            // Display grouped pieces
            for (const [pieceType, count] of Object.entries(groupedCaptures)) {
                const pieceIcon = createPieceIcon(pieceType, 'white');
                pieceIcon.style.margin = '3px';
                
                // If there are multiple pieces of the same type, add a count badge
                if (count > 1) {
                    const countBadge = document.createElement('span');
                    countBadge.textContent = count;
                    countBadge.style.position = 'absolute';
                    countBadge.style.right = '-5px';
                    countBadge.style.bottom = '-5px';
                    countBadge.style.backgroundColor = '#4CAF50';
                    countBadge.style.color = 'white';
                    countBadge.style.borderRadius = '50%';
                    countBadge.style.width = '16px';
                    countBadge.style.height = '16px';
                    countBadge.style.fontSize = '12px';
                    countBadge.style.display = 'flex';
                    countBadge.style.justifyContent = 'center';
                    countBadge.style.alignItems = 'center';
                    pieceIcon.appendChild(countBadge);
                }
                
                blackCapturesContent.appendChild(pieceIcon);
            }
        }
    }
}

// Create an icon for a piece type
function createPieceIcon(pieceType, color) {
    const container = document.createElement('div');
    container.style.width = '24px';
    container.style.height = '24px';
    container.style.position = 'relative';
    container.style.display = 'inline-block';
    
    const pieceSymbol = getPieceUnicode(pieceType, color);
    container.textContent = pieceSymbol;
    container.style.fontSize = '22px';
    container.style.lineHeight = '24px';
    container.style.textAlign = 'center';
    container.style.color = color === 'white' ? '#FFFFFF' : '#000000';
    container.style.textShadow = color === 'white' ? '0 0 2px #000' : '0 0 2px #FFF';
    
    return container;
}

// Get unicode symbol for chess piece
function getPieceUnicode(pieceType, color) {
    if (color === 'white') {
        switch(pieceType) {
            case 'king': return '♔';
            case 'queen': return '♕';
            case 'rook': return '♖';
            case 'bishop': return '♗';
            case 'knight': return '♘';
            case 'pawn': return '♙';
            default: return '?';
        }
    } else {
        switch(pieceType) {
            case 'king': return '♚';
            case 'queen': return '♛';
            case 'rook': return '♜';
            case 'bishop': return '♝';
            case 'knight': return '♞';
            case 'pawn': return '♟';
            default: return '?';
        }
    }
}

// Create settings panel
function createSettingsPanel() {
    // Create container for settings panel
    var settingsPanel = document.createElement('div');
    settingsPanel.id = 'settings-panel';
    settingsPanel.style.position = 'absolute';
    settingsPanel.style.bottom = '20px';
    settingsPanel.style.right = '20px';
    settingsPanel.style.width = '250px';
    settingsPanel.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
    settingsPanel.style.color = 'white';
    settingsPanel.style.borderRadius = '10px';
    settingsPanel.style.padding = '15px';
    settingsPanel.style.fontFamily = 'Arial, sans-serif';
    settingsPanel.style.zIndex = '10';
    settingsPanel.style.boxShadow = '0 4px 8px rgba(0,0,0,0.3)';
    settingsPanel.style.display = 'none'; // Hidden by default
    
    // Create panel title
    var title = document.createElement('h3');
    title.textContent = 'Oyun Ayarları';
    title.style.textAlign = 'center';
    title.style.marginTop = '0';
    title.style.marginBottom = '10px';
    title.style.borderBottom = '1px solid rgba(255, 255, 255, 0.3)';
    title.style.paddingBottom = '5px';
    settingsPanel.appendChild(title);
    
    // Create settings form
    var settingsForm = document.createElement('form');
    settingsForm.id = 'settings-form';
    settingsForm.style.display = 'flex';
    settingsForm.style.flexDirection = 'column';
    settingsForm.style.gap = '10px';
    
    // Board color settings
    appendColorSetting(settingsForm, 'boardLightColor', 'Açık Kare Rengi', gameSettings.boardLightColor);
    appendColorSetting(settingsForm, 'boardDarkColor', 'Koyu Kare Rengi', gameSettings.boardDarkColor);
    appendColorSetting(settingsForm, 'boardBorderColor', 'Tahta Kenarlık Rengi', gameSettings.boardBorderColor);
    
    // Camera settings
    appendRangeSetting(settingsForm, 'cameraDistance', 'Kamera Mesafesi', gameSettings.cameraDistance, 5, 20, 0.5);
    appendRangeSetting(settingsForm, 'cameraAngle', 'Kamera Açısı', gameSettings.cameraAngle, 0, 90, 1);
    
    // Show coordinates toggle
    appendCheckboxSetting(settingsForm, 'showCoordinates', 'Koordinatları Göster', gameSettings.showCoordinates);
    
    // Apply button
    var applyButton = document.createElement('button');
    applyButton.textContent = 'Uygula';
    applyButton.style.backgroundColor = '#4CAF50';
    applyButton.style.color = 'white';
    applyButton.style.border = 'none';
    applyButton.style.padding = '10px';
    applyButton.style.borderRadius = '5px';
    applyButton.style.cursor = 'pointer';
    applyButton.style.marginTop = '10px';
    applyButton.style.fontWeight = 'bold';
    
    applyButton.onclick = function(event) {
        event.preventDefault();
        applySettings();
    };
    
    settingsForm.appendChild(applyButton);
    settingsPanel.appendChild(settingsForm);
    
    // Create toggle button for settings panel
    var toggleButton = document.createElement('button');
    toggleButton.id = 'toggle-settings';
    toggleButton.textContent = '⚙️ Ayarlar';
    toggleButton.style.position = 'absolute';
    toggleButton.style.bottom = '20px';
    toggleButton.style.right = '20px';
    toggleButton.style.backgroundColor = '#4CAF50';
    toggleButton.style.color = 'white';
    toggleButton.style.border = 'none';
    toggleButton.style.padding = '10px 15px';
    toggleButton.style.borderRadius = '5px';
    toggleButton.style.cursor = 'pointer';
    toggleButton.style.zIndex = '11';
    toggleButton.style.fontWeight = 'bold';
    
    toggleButton.onclick = function() {
        if (settingsPanel.style.display === 'none') {
            settingsPanel.style.display = 'block';
            toggleButton.style.display = 'none';
        } else {
            settingsPanel.style.display = 'none';
        }
    };
    
    // Close button for settings panel
    var closeButton = document.createElement('button');
    closeButton.textContent = 'X';
    closeButton.style.position = 'absolute';
    closeButton.style.top = '10px';
    closeButton.style.right = '10px';
    closeButton.style.backgroundColor = 'transparent';
    closeButton.style.color = 'white';
    closeButton.style.border = 'none';
    closeButton.style.cursor = 'pointer';
    closeButton.style.fontSize = '16px';
    closeButton.style.fontWeight = 'bold';
    
    closeButton.onclick = function() {
        settingsPanel.style.display = 'none';
        toggleButton.style.display = 'block';
    };
    
    settingsPanel.appendChild(closeButton);
    
    document.body.appendChild(settingsPanel);
    document.body.appendChild(toggleButton);
}

// Helper function to add a color picker setting
function appendColorSetting(form, id, label, defaultValue) {
    var container = document.createElement('div');
    container.style.display = 'flex';
    container.style.justifyContent = 'space-between';
    container.style.alignItems = 'center';
    
    var labelElement = document.createElement('label');
    labelElement.setAttribute('for', id);
    labelElement.textContent = label;
    
    var input = document.createElement('input');
    input.setAttribute('type', 'color');
    input.setAttribute('id', id);
    input.setAttribute('name', id);
    input.value = rgbToHex(defaultValue);
    
    container.appendChild(labelElement);
    container.appendChild(input);
    form.appendChild(container);
}

// Helper function to add a range slider setting
function appendRangeSetting(form, id, label, defaultValue, min, max, step) {
    var container = document.createElement('div');
    container.style.display = 'flex';
    container.style.flexDirection = 'column';
    
    var labelElement = document.createElement('label');
    labelElement.setAttribute('for', id);
    labelElement.textContent = label;
    
    var rangeContainer = document.createElement('div');
    rangeContainer.style.display = 'flex';
    rangeContainer.style.alignItems = 'center';
    rangeContainer.style.gap = '10px';
    
    var input = document.createElement('input');
    input.setAttribute('type', 'range');
    input.setAttribute('id', id);
    input.setAttribute('name', id);
    input.setAttribute('min', min);
    input.setAttribute('max', max);
    input.setAttribute('step', step);
    input.setAttribute('value', defaultValue);
    input.style.flex = '1';
    
    var valueDisplay = document.createElement('span');
    valueDisplay.textContent = defaultValue;
    valueDisplay.style.minWidth = '40px';
    valueDisplay.style.textAlign = 'center';
    
    input.oninput = function() {
        valueDisplay.textContent = this.value;
    };
    
    rangeContainer.appendChild(input);
    rangeContainer.appendChild(valueDisplay);
    
    container.appendChild(labelElement);
    container.appendChild(rangeContainer);
    form.appendChild(container);
}

// Helper function to add a checkbox setting
function appendCheckboxSetting(form, id, label, defaultValue) {
    var container = document.createElement('div');
    container.style.display = 'flex';
    container.style.justifyContent = 'space-between';
    container.style.alignItems = 'center';
    
    var labelElement = document.createElement('label');
    labelElement.setAttribute('for', id);
    labelElement.textContent = label;
    
    var input = document.createElement('input');
    input.setAttribute('type', 'checkbox');
    input.setAttribute('id', id);
    input.setAttribute('name', id);
    input.checked = defaultValue;
    
    container.appendChild(labelElement);
    container.appendChild(input);
    form.appendChild(container);
}

// Function to convert RGB hex number to hex color string
function rgbToHex(rgb) {
    return '#' + rgb.toString(16).padStart(6, '0');
}

// Function to convert hex color string to RGB number
function hexToRgb(hex) {
    return parseInt(hex.replace('#', ''), 16);
}

// Apply settings from the form to the game
function applySettings() {
    // Get values from form
    gameSettings.boardLightColor = hexToRgb(document.getElementById('boardLightColor').value);
    gameSettings.boardDarkColor = hexToRgb(document.getElementById('boardDarkColor').value);
    gameSettings.boardBorderColor = hexToRgb(document.getElementById('boardBorderColor').value);
    gameSettings.cameraDistance = parseFloat(document.getElementById('cameraDistance').value);
    gameSettings.cameraAngle = parseFloat(document.getElementById('cameraAngle').value);
    gameSettings.showCoordinates = document.getElementById('showCoordinates').checked;
    
    // Apply settings to the game
    updateBoardColors();
    updateCameraPosition();
    updateCoordinatesVisibility();
}

// Update board colors based on current settings
function updateBoardColors() {
    const chessBoard = scene.getObjectByName('chessBoard');
    if (!chessBoard) return;
    
    chessBoard.children.forEach(child => {
        if (child.userData.isBorder) {
            // Update border color
            if (child.material) {
                child.material.color.setHex(gameSettings.boardBorderColor);
            }
        } else if (child.userData.boardPosition) {
            // Update square colors
            const { col, row } = child.userData.boardPosition;
            const isLight = (col + row) % 2 === 0;
            
            if (child.material) {
                child.material.color.setHex(isLight ? gameSettings.boardLightColor : gameSettings.boardDarkColor);
            }
        }
    });
}

// Update camera position based on current settings
function updateCameraPosition() {
    if (!camera) return;
    
    const angleRad = (gameSettings.cameraAngle * Math.PI) / 180;
    const distance = gameSettings.cameraDistance;
    
    camera.position.y = distance * Math.sin(angleRad);
    const horizontalDistance = distance * Math.cos(angleRad);
    camera.position.z = horizontalDistance;
    camera.position.x = 0;
    
    camera.lookAt(0, 0, 0);
}

// Update coordinates visibility based on current settings
function updateCoordinatesVisibility() {
    const chessBoard = scene.getObjectByName('chessBoard');
    if (!chessBoard) return;
    
    chessBoard.children.forEach(child => {
        if (child.userData.isCoordinate) {
            child.visible = gameSettings.showCoordinates;
        }
    });
}

// Game save/load system
function saveGame() {
    const gameState = {
        board: board.map(row => 
            row.map(cell => cell ? {
                type: cell.type,
                color: cell.color
            } : null)
        ),
        currentTurn: currentTurn,
        moveHistory: moveHistory,
        capturedPieces: capturedPieces,
        pieceHasMoved: pieceHasMoved,
        timestamp: new Date().toISOString()
    };
    
    // Generate a unique ID for the save
    const saveId = 'chess_save_' + new Date().getTime();
    
    // Save to localStorage
    try {
        localStorage.setItem(saveId, JSON.stringify(gameState));
        
        // Update saves list
        let savedGames = JSON.parse(localStorage.getItem('chess_saved_games') || '[]');
        savedGames.push({
            id: saveId,
            timestamp: gameState.timestamp,
            moveCount: moveHistory.length
        });
        localStorage.setItem('chess_saved_games', JSON.stringify(savedGames));
        
        // Show success message
        showNotification('Oyun başarıyla kaydedildi!', 'success');
        updateSavedGamesList();
    } catch (error) {
        showNotification('Oyun kaydedilemedi: ' + error.message, 'error');
    }
}

function loadGame(saveId) {
    try {
        const savedState = JSON.parse(localStorage.getItem(saveId));
        if (!savedState) {
            throw new Error('Kayıtlı oyun bulunamadı');
        }
        
        // Clear current game state
        clearGame();
        
        // Restore board state
        savedState.board.forEach((row, rowIndex) => {
            row.forEach((cell, colIndex) => {
                if (cell) {
                    const piece = createChessPiece(cell.type, cell.color);
                    pieces.add(positionPiece(piece, colIndex, rowIndex, 1));
                }
            });
        });
        
        // Restore game state
        currentTurn = savedState.currentTurn;
        moveHistory = savedState.moveHistory;
        capturedPieces = savedState.capturedPieces;
        pieceHasMoved = savedState.pieceHasMoved;
        
        // Update UI
        updateBoardState();
        updateMoveHistoryPanel();
        updateCapturedPiecesPanel();
        updateGameInfo();
        
        showNotification('Oyun başarıyla yüklendi!', 'success');
    } catch (error) {
        showNotification('Oyun yüklenemedi: ' + error.message, 'error');
    }
}

function clearGame() {
    // Remove all pieces from the scene
    while(pieces.children.length > 0) {
        pieces.remove(pieces.children[0]);
    }
    
    // Reset game state
    board = Array(8).fill().map(() => Array(8).fill(null));
    currentTurn = 'white';
    moveHistory = [];
    capturedPieces = { white: [], black: [] };
    pieceHasMoved = {};
    promotionInProgress = null;
    enPassantTarget = null;
    
    // Clear UI
    updateMoveHistoryPanel();
    updateCapturedPiecesPanel();
    updateGameInfo();
    
    // Clear captured pieces from pockets
    const chessBoard = scene.getObjectByName('chessBoard');
    if (chessBoard) {
        const whitePocket = chessBoard.getObjectByName('whitePocket');
        const blackPocket = chessBoard.getObjectByName('blackPocket');
        
        if (whitePocket) {
            while (whitePocket.children.length > 4) { // Keep the pocket structure (4 walls)
                whitePocket.remove(whitePocket.children[whitePocket.children.length - 1]);
            }
        }
        
        if (blackPocket) {
            while (blackPocket.children.length > 4) { // Keep the pocket structure (4 walls)
                blackPocket.remove(blackPocket.children[blackPocket.children.length - 1]);
            }
        }
    }
}

function exportToPGN() {
    let pgn = '';
    
    // Add metadata
    pgn += '[Event "Casual Game"]\n';
    pgn += '[Site "Web Chess"]\n';
    pgn += `[Date "${new Date().toISOString().split('T')[0]}"]\n`;
    pgn += '[White "Player 1"]\n';
    pgn += '[Black "Player 2"]\n';
    
    // Add moves
    for (let i = 0; i < moveHistory.length; i += 2) {
        const moveNumber = Math.floor(i / 2) + 1;
        const whiteMove = moveHistory[i];
        const blackMove = i + 1 < moveHistory.length ? moveHistory[i + 1] : null;
        
        pgn += `${moveNumber}. ${whiteMove.notation}${blackMove ? ' ' + blackMove.notation : ''} `;
    }
    
    // Create and trigger download
    const blob = new Blob([pgn], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'chess_game.pgn';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// UI elements for game management
function createGameManagementUI() {
    // Create container
    const container = document.createElement('div');
    container.id = 'game-management';
    container.style.position = 'absolute';
    container.style.bottom = '20px';
    container.style.left = '20px';
    container.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
    container.style.padding = '15px';
    container.style.borderRadius = '10px';
    container.style.color = 'white';
    container.style.zIndex = '10';
    
    // Save button
    const saveButton = document.createElement('button');
    saveButton.textContent = '💾 Oyunu Kaydet';
    saveButton.onclick = saveGame;
    styleButton(saveButton);
    
    // Load button
    const loadButton = document.createElement('button');
    loadButton.textContent = '📂 Oyun Yükle';
    loadButton.onclick = () => {
        const savedGamesPanel = document.getElementById('saved-games-panel');
        if (savedGamesPanel) {
            savedGamesPanel.style.display = savedGamesPanel.style.display === 'none' ? 'block' : 'none';
        }
    };
    styleButton(loadButton);
    
    // Export PGN button
    const exportButton = document.createElement('button');
    exportButton.textContent = '📤 PGN Olarak Dışa Aktar';
    exportButton.onclick = exportToPGN;
    styleButton(exportButton);
    
    // New Game button
    const newGameButton = document.createElement('button');
    newGameButton.textContent = '🔄 Yeni Oyun';
    newGameButton.onclick = () => {
        if (confirm('Yeni bir oyun başlatmak istediğinizden emin misiniz?')) {
            clearGame();
            createAllPieces(scene.children[0].position);
        }
    };
    styleButton(newGameButton);
    
    container.appendChild(saveButton);
    container.appendChild(loadButton);
    container.appendChild(exportButton);
    container.appendChild(newGameButton);
    
    // Create saved games panel
    createSavedGamesPanel();
    
    document.body.appendChild(container);
}

function createSavedGamesPanel() {
    const panel = document.createElement('div');
    panel.id = 'saved-games-panel';
    panel.style.position = 'absolute';
    panel.style.bottom = '100px';
    panel.style.left = '20px';
    panel.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
    panel.style.padding = '15px';
    panel.style.borderRadius = '10px';
    panel.style.color = 'white';
    panel.style.zIndex = '11';
    panel.style.display = 'none';
    panel.style.maxHeight = '300px';
    panel.style.overflowY = 'auto';
    
    const title = document.createElement('h3');
    title.textContent = 'Kayıtlı Oyunlar';
    title.style.marginTop = '0';
    panel.appendChild(title);
    
    const savedGamesList = document.createElement('div');
    savedGamesList.id = 'saved-games-list';
    panel.appendChild(savedGamesList);
    
    document.body.appendChild(panel);
    updateSavedGamesList();
}

function updateSavedGamesList() {
    const list = document.getElementById('saved-games-list');
    if (!list) return;
    
    list.innerHTML = '';
    
    const savedGames = JSON.parse(localStorage.getItem('chess_saved_games') || '[]');
    
    if (savedGames.length === 0) {
        list.innerHTML = '<p>Kayıtlı oyun bulunamadı</p>';
        return;
    }
    
    savedGames.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
    
    savedGames.forEach(save => {
        const saveItem = document.createElement('div');
        saveItem.style.padding = '10px';
        saveItem.style.margin = '5px 0';
        saveItem.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
        saveItem.style.borderRadius = '5px';
        saveItem.style.display = 'flex';
        saveItem.style.justifyContent = 'space-between';
        saveItem.style.alignItems = 'center';
        
        const saveInfo = document.createElement('div');
        saveInfo.innerHTML = `
            <div>Tarih: ${new Date(save.timestamp).toLocaleString()}</div>
            <div>Hamle Sayısı: ${save.moveCount}</div>
        `;
        
        const loadButton = document.createElement('button');
        loadButton.textContent = 'Yükle';
        styleButton(loadButton);
        loadButton.onclick = () => loadGame(save.id);
        
        saveItem.appendChild(saveInfo);
        saveItem.appendChild(loadButton);
        list.appendChild(saveItem);
    });
}

function styleButton(button) {
    button.style.backgroundColor = '#4CAF50';
    button.style.color = 'white';
    button.style.border = 'none';
    button.style.padding = '8px 12px';
    button.style.margin = '5px';
    button.style.borderRadius = '5px';
    button.style.cursor = 'pointer';
    button.style.fontSize = '14px';
    
    button.onmouseover = function() {
        this.style.backgroundColor = '#45a049';
    };
    button.onmouseout = function() {
        this.style.backgroundColor = '#4CAF50';
    };
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.left = '50%';
    notification.style.transform = 'translateX(-50%)';
    notification.style.padding = '10px 20px';
    notification.style.borderRadius = '5px';
    notification.style.color = 'white';
    notification.style.zIndex = '1000';
    notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.5s';
        setTimeout(() => document.body.removeChild(notification), 500);
    }, 3000);
}

// Add captured pieces pockets to the board
function createCapturedPiecesPockets() {
    const pocketsGroup = new THREE.Group();
    pocketsGroup.name = 'capturedPiecesPockets';
    
    // Create white's pocket (right side, facing left)
    const whitePocket = createPocket();
    whitePocket.position.set(5.5, 0, 0);
    whitePocket.rotation.y = Math.PI; // Rotate 180 degrees to face left
    whitePocket.name = 'whitePocket';
    pocketsGroup.add(whitePocket);
    
    // Create black's pocket (left side, facing right)
    const blackPocket = createPocket();
    blackPocket.position.set(-5.5, 0, 0);
    blackPocket.name = 'blackPocket';
    pocketsGroup.add(blackPocket);
    
    return pocketsGroup;
}

// Create a single pocket
function createPocket() {
    const pocketGroup = new THREE.Group();
    
    // Create the base (smaller and more elegant)
    const baseGeometry = new THREE.BoxGeometry(1.5, 0.1, 8);
    const baseMaterial = new THREE.MeshPhongMaterial({
        color: gameSettings.boardBorderColor,
        shininess: 30,
        specular: 0x222222,
        transparent: true,
        opacity: 0.7
    });
    const base = new THREE.Mesh(baseGeometry, baseMaterial);
    base.receiveShadow = true;
    pocketGroup.add(base);
    
    // Create walls with subtle aesthetics
    const wallMaterial = new THREE.MeshPhongMaterial({
        color: gameSettings.boardBorderColor,
        shininess: 30,
        specular: 0x222222,
        transparent: true,
        opacity: 0.5
    });
    
    // Back wall (subtle height)
    const backWall = new THREE.Mesh(
        new THREE.BoxGeometry(0.1, 0.3, 8),
        wallMaterial
    );
    backWall.position.set(0.7, 0.15, 0);
    backWall.receiveShadow = true;
    backWall.castShadow = true;
    pocketGroup.add(backWall);
    
    // Side walls (minimal)
    const sideWallGeometry = new THREE.BoxGeometry(1.5, 0.2, 0.1);
    
    const topWall = new THREE.Mesh(sideWallGeometry, wallMaterial);
    topWall.position.set(0, 0.1, 4);
    topWall.receiveShadow = true;
    topWall.castShadow = true;
    pocketGroup.add(topWall);
    
    const bottomWall = new THREE.Mesh(sideWallGeometry, wallMaterial);
    bottomWall.position.set(0, 0.1, -4);
    bottomWall.receiveShadow = true;
    bottomWall.castShadow = true;
    pocketGroup.add(bottomWall);
    
    return pocketGroup;
}