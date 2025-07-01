<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
class studentController extends Controller
{
    /**
     * Muestra una lista de los estudiantes.
     */
    public function index()
    {
        // Lógica para obtener y devolver una lista de estudiantes
        $students = Student::all();
        if ($students->isEmpty()) {
            $data = [
                'message' => 'No se encontraron estudiantes',
                'status' => '200',
                'count' => 0,
            ];
            // Retorna una respuesta 200 si no se encuentran estudiantes
            return response()->json(['message' => 'No se encontraron estudiantes'], 200);
        }
        $data = [
            'students' => $students,
            'count' => $students->count(),
            'message' => 'Estudiantes recuperados exitosamente',
            'status' => '200',
        ];
        return response()->json($data,200);
        
    }
    public function show($id)
    {
        // Lógica para obtener un estudiante específico por ID
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => '404',
            ];
            return response()->json($data, 404);
        }
        $data = [
            'student' => $student,
            'message' => 'Estudiante recuperado exitosamente',
            'status' => '200',
        ];
        return response()->json($data, 200);
    }
    
    public function store(Request $request)
    {
        $phoneRegex = '/^\+?\d{1,3}-\d{1,3}-\d{3,4}$/';
        // Validar los datos de la solicitud
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:15| regex:' . $phoneRegex,
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
        ]);
        if ($validate->fails()) {
            $data = [
                'message' => 'La validación falló',
                'status' => '400',
                'errors' => $validate->errors(),
            ];

            return response()->json($data, 400);
        }

        // Crear un nuevo registro de estudiante
        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
        ]);
        if (!$student) {
            $data = [
                'message' => 'No se pudo crear el estudiante',
                'status' => '500',
            ];
            return response()->json($data, 500);
        }
        $data = [
            'message' => 'Estudiante creado exitosamente',
            'status' => '201',
            'student' => $student,
        ];

        return response()->json($data, 201);
    }
    public function update(Request $request, $id)
    {
        // Buscar el estudiante por ID
        $student = Student::find($id); 
        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => '404',
            ];
            return response()->json($data, 404);
        }
        // Expresión regular para validar el teléfono, ejemplo +595-263-715
        $phoneRegex = '/^\+?\d{1,3}-\d{1,3}-\d{3,4}$/';
        // Validar los datos de la solicitud
        $validate = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:students,email,' . $id,
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15| regex:' . $phoneRegex,
        ]);
        if ($validate->fails()) {
            $data = [
                'message' => 'La validación falló',
                'status' => '400',
                'errors' => $validate->errors(),
            ];
            return response()->json($data, 400);
        }

        // Actualizar el registro del estudiante
        $student->update($request->all());
        $data = [
            'message' => 'Estudiante actualizado exitosamente',
            'status' => '200',
            'student' => $student,
        ];
        return response()->json($data, 200);
    }
    public function updatePartial(Request $request, $id)
    {
        // Buscar el estudiante por ID
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => '404',
            ];
            return response()->json($data, 404);
        }
        // Expresión regular para validar el teléfono, ejemplo +595-263-715
        $phoneRegex = '/^\+?\d{1,3}-\d{1,3}-\d{3,4}$/';
        // Validar los datos de la solicitud
        $validate = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:students,email,' . $id,
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15| regex:' . $phoneRegex,
        ]);
        if ($validate->fails()) {
            $data = [
                'message' => 'La validación falló',
                'status' => '400',
                'errors' => $validate->errors(),
            ];
            return response()->json($data, 400);
        }

        // Actualizar parcialmente el registro del estudiante
        $student->update($request->only(['name', 'email', 'phone', 'date_of_birth', 'address']));
        $data = [
            'message' => 'Estudiante actualizado exitosamente',
            'status' => '200',
            'student' => $student,
        ];
        return response()->json($data, 200);
    }
    public function destroy($id)
    {
        // Buscar el estudiante por ID
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => '404',
            ];
            return response()->json($data, 404);
        }

        // Eliminar el registro del estudiante
        $student->delete();
        $data = [
            'message' => 'Estudiante eliminado exitosamente',
            'status' => '200',
        ];
        return response()->json($data, 200);
    }
    public function search(Request $request)
    {
        // Validar la consulta de búsqueda
        $validate = Validator::make($request->all(), [
            'query' => 'required|string|max:255',
        ]);
        if ($validate->fails()) {
            $data = [
                'message' => 'La validación falló',
                'status' => '400',
                'errors' => $validate->errors(),
            ];
            return response()->json($data, 400);
        }

        // Buscar estudiantes por nombre o correo electrónico
        $query = $request->input('query');
        $students = Student::where('name', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->get();

        if ($students->isEmpty()) {
            $data = [
                'message' => 'No se encontraron estudiantes que coincidan con la búsqueda',
                'status' => '404',
            ];
            return response()->json($data, 404);
        }

        $data = [
            'students' => $students,
            'count' => $students->count(),
            'message' => 'Estudiantes recuperados exitosamente',
            'status' => '200',
        ];
        return response()->json($data, 200);
    }

}
